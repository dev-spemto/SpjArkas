<?php

namespace App\Http\Controllers;

use App\Models\AccountCode;
use App\Models\ActivityCode;
use App\Models\SchoolProfile;
use App\Models\Spj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $jenisBos = $request->query('jenis_bos');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $spjs = Spj::when($search, function ($query, $search) {
            return $query->where('no_bkp', 'like', "%{$search}%")
                         ->orWhere('uraian', 'like', "%{$search}%")
                         ->orWhere('penerima_toko', 'like', "%{$search}%")
                         ->orWhere('kode_kegiatan', 'like', "%{$search}%")
                         ->orWhere('kode_rekening', 'like', "%{$search}%");
        })->when($jenisBos, function ($query, $jenisBos) {
            return $query->where('jenis_bos', $jenisBos);
        })->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->orderBy('tanggal_transaksi', 'asc')->orderBy('id', 'asc')->paginate(20)->withQueryString();

        $saldoAwal = 0;
        if ($spjs->firstItem()) {
            $firstItem = $spjs->first();
            
            $prevPenerimaan = Spj::where(function($q) use ($firstItem) {
                $q->where('tanggal_transaksi', '<', $firstItem->tanggal_transaksi)
                  ->orWhere(function($q2) use ($firstItem) {
                      $q2->where('tanggal_transaksi', $firstItem->tanggal_transaksi)
                         ->where('id', '<', $firstItem->id);
                  });
            })->where('jenis_transaksi', 'Penerimaan')->sum('nominal');

            $prevPengeluaran = Spj::where(function($q) use ($firstItem) {
                $q->where('tanggal_transaksi', '<', $firstItem->tanggal_transaksi)
                  ->orWhere(function($q2) use ($firstItem) {
                      $q2->where('tanggal_transaksi', $firstItem->tanggal_transaksi)
                         ->where('id', '<', $firstItem->id);
                  });
            })->where('jenis_transaksi', 'Pengeluaran')->sum('nominal');

            $saldoAwal = $prevPenerimaan - $prevPengeluaran;
        }

        $totalPenerimaan = $spjs->where('jenis_transaksi', 'Penerimaan')->sum('nominal');
        $totalPengeluaran = $spjs->where('jenis_transaksi', 'Pengeluaran')->sum('nominal');

        return view('spjs.index', compact('spjs', 'search', 'jenisBos', 'startDate', 'endDate', 'saldoAwal', 'totalPenerimaan', 'totalPengeluaran'));
    }

    public function create()
    {
        $accountCodes = AccountCode::orderBy('kode_rekening')->get();
        $activityCodes = ActivityCode::orderBy('kode_kegiatan')->get();

        return view('spjs.create', compact('accountCodes', 'activityCodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_bkp' => 'nullable|string|max:100',
            'tanggal_transaksi' => 'required|date',
            'jenis_bos' => 'required|in:Reguler,Kinerja,Daerah,Afirmasi',
            'jenis_transaksi' => 'required|in:Pengeluaran,Penerimaan',
            'kode_kegiatan' => 'nullable|string|max:100',
            'nama_kegiatan' => 'nullable|string|max:255',
            'kode_rekening' => 'nullable|string|max:100',
            'nama_rekening' => 'nullable|string|max:255',
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'penerima_toko' => 'nullable|string|max:255',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['sumber_input'] = 'manual';

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf_path'] = $request->file('file_pdf')->store('spjs/pdf', 'public');
        }

        Spj::create($validated);

        return redirect()->route('spjs.index')->with('success', 'Transaksi SPJ berhasil ditambahkan.');
    }

    public function edit(Spj $spj)
    {
        $accountCodes = AccountCode::orderBy('kode_rekening')->get();
        $activityCodes = ActivityCode::orderBy('kode_kegiatan')->get();

        return view('spjs.edit', compact('spj', 'accountCodes', 'activityCodes'));
    }

    public function update(Request $request, Spj $spj)
    {
        $validated = $request->validate([
            'no_bkp' => 'nullable|string|max:100',
            'tanggal_transaksi' => 'required|date',
            'jenis_bos' => 'required|in:Reguler,Kinerja,Daerah,Afirmasi',
            'jenis_transaksi' => 'required|in:Pengeluaran,Penerimaan',
            'kode_kegiatan' => 'nullable|string|max:100',
            'nama_kegiatan' => 'nullable|string|max:255',
            'kode_rekening' => 'nullable|string|max:100',
            'nama_rekening' => 'nullable|string|max:255',
            'uraian' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'penerima_toko' => 'nullable|string|max:255',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($spj->file_pdf_path && Storage::disk('public')->exists($spj->file_pdf_path)) {
                Storage::disk('public')->delete($spj->file_pdf_path);
            }
            $validated['file_pdf_path'] = $request->file('file_pdf')->store('spjs/pdf', 'public');
        }

        $spj->update($validated);

        return redirect()->route('spjs.index')->with('success', 'Transaksi SPJ berhasil diperbarui.');
    }

    public function destroy(Spj $spj)
    {
        if ($spj->file_pdf_path && Storage::disk('public')->exists($spj->file_pdf_path)) {
            Storage::disk('public')->delete($spj->file_pdf_path);
        }

        $spj->delete();

        return redirect()->back()->with('success', 'Transaksi SPJ berhasil dihapus.');
    }

    public function printBundle(Request $request)
    {
        $schoolProfile = SchoolProfile::first();
        $type = $request->query('type', 'a2');
        $ids = $request->query('ids');

        $query = Spj::query();

        if ($ids) {
            $idArray = explode(',', $ids);
            $query->whereIn('id', $idArray);
        } else {
            $query->latest('tanggal_transaksi')->take(1);
        }

        // Filter otomatis: Hanya ambil transaksi pengeluaran/pembelian yang memiliki No. BKP / BPU dan Kode Rekening/Kegiatan
        $rawSpjs = $query->where('jenis_transaksi', 'Pengeluaran')
            ->whereNotNull('no_bkp')
            ->where('no_bkp', '!=', '')
            ->where('no_bkp', '!=', '-')
            ->where(function ($q) {
                $q->whereNotNull('kode_rekening')->where('kode_rekening', '!=', '')->where('kode_rekening', '!=', '-')
                  ->orWhereNotNull('kode_kegiatan')->where('kode_kegiatan', '!=', '')->where('kode_kegiatan', '!=', '-');
            })
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        if (in_array($type, ['a2_bpu', 'umum_bpu'])) {
            $bkpNumbers = $rawSpjs->pluck('no_bkp')->filter()->unique();

            if ($bkpNumbers->count() > 0) {
                $allGroupedSpjs = Spj::whereIn('no_bkp', $bkpNumbers)->orderBy('tanggal_transaksi', 'asc')->get()->groupBy('no_bkp');
            } else {
                $allGroupedSpjs = $rawSpjs->groupBy('id');
            }

            $spjs = collect();
            foreach ($allGroupedSpjs as $noBkp => $group) {
                $first = $group->first();
                $totalNominal = $group->sum('nominal');
                
                $uraianList = $group->pluck('uraian')->filter()->unique()->implode(', ');
                
                $kegiatanList = $group->map(function($i) {
                    return $i->kode_kegiatan ? $i->kode_kegiatan . ($i->nama_kegiatan ? ' - ' . $i->nama_kegiatan : '') : null;
                })->filter()->unique()->implode('; ');

                $rekeningList = $group->map(function($i) {
                    return $i->kode_rekening ? $i->kode_rekening . ($i->nama_rekening ? ' - ' . $i->nama_rekening : '') : null;
                })->filter()->unique()->implode('; ');

                $item = clone $first;
                $item->nominal = $totalNominal;
                $item->uraian = $uraianList;
                $item->kegiatan_combined = $kegiatanList ?: '-';
                $item->rekening_combined = $rekeningList ?: '-';
                $item->jumlah_item = $group->count();
                $item->terbilang_text = $this->terbilang((int) $totalNominal) . ' Rupiah';

                $spjs->push($item);
            }
        } else {
            $spjs = $rawSpjs;
            foreach ($spjs as $spj) {
                $spj->kegiatan_combined = $spj->kode_kegiatan ? $spj->kode_kegiatan . ($spj->nama_kegiatan ? ' - ' . $spj->nama_kegiatan : '') : '-';
                $spj->rekening_combined = $spj->kode_rekening ? $spj->kode_rekening . ($spj->nama_rekening ? ' - ' . $spj->nama_rekening : '') : '-';
                $spj->jumlah_item = 1;
                $spj->terbilang_text = $this->terbilang((int) $spj->nominal) . ' Rupiah';
            }
        }

        return view('spjs.print.bundle', compact('spjs', 'schoolProfile', 'type', 'ids'));
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $terbilang = "";

        if ($angka < 12) {
            $terbilang = " " . $baca[$angka];
        } else if ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10) . " Belas";
        } else if ($angka < 100) {
            $terbilang = $this->terbilang(floor($angka / 10)) . " Puluh " . $this->terbilang($angka % 10);
        } else if ($angka < 200) {
            $terbilang = " Seratus " . $this->terbilang($angka - 100);
        } else if ($angka < 1000) {
            $terbilang = $this->terbilang(floor($angka / 100)) . " Ratus " . $this->terbilang($angka % 100);
        } else if ($angka < 2000) {
            $terbilang = " Seribu " . $this->terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $terbilang = $this->terbilang(floor($angka / 1000)) . " Ribu " . $this->terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $terbilang = $this->terbilang(floor($angka / 1000000)) . " Juta " . $this->terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $terbilang = $this->terbilang(floor($angka / 1000000000)) . " Milyar " . $this->terbilang(fmod($angka, 1000000000));
        }

        return preg_replace('/\s+/', ' ', trim($terbilang));
    }
}