<?php

namespace App\Http\Controllers;

use App\Models\BospDocument;
use App\Models\Spj;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');

        // 1. Progress Pengerjaan BOSP berdasarkan tanggal_transaksi SPJ terakhir
        $latestSpj = Spj::whereYear('tanggal_transaksi', $currentYear)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->latest('tanggal_transaksi')
            ->first();

        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $latestMonthNumber = $latestSpj ? (int) Carbon::parse($latestSpj->tanggal_transaksi)->format('m') : 0;
        $latestMonthName = $latestMonthNumber > 0 ? $bulanIndo[$latestMonthNumber] : 'Belum Ada Transaksi';
        $progressPercentage = round(($latestMonthNumber / 12) * 100);

        // 2. Tahun BOSP Selesai
        $completedYears = BospDocument::whereNotNull('file_path')
            ->where('file_path', '!=', '')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // 3. Ringkasan Kas & Saldo Per Sumber Dana (Tahun Berjalan)
        $sumberDana = ['Reguler', 'Afirmasi', 'Kinerja', 'Daerah'];
        $ringkasanBos = [];
        $totalSaldoSeluruhnya = 0;

        foreach ($sumberDana as $jenis) {
            // A. Pagu Diterima (Hanya pencairan Dana BOSP Murni)
            $paguDiterima = Spj::where('jenis_bos', $jenis)
                ->whereYear('tanggal_transaksi', $currentYear)
                ->where('jenis_transaksi', 'Penerimaan')
                ->where(function($q) {
                    $q->where('uraian', 'like', '%Terima Dana%')
                      ->orWhere('uraian', 'like', '%Penerimaan Dana%')
                      ->orWhere('uraian', 'like', '%Pencairan%');
                })
                ->sum('nominal');

            // B. Total Pembelanjaan Murni (Mengecualikan Tarik Tunai, Pindahan Kas, Pajak Bunga, & Bunga Bank)
            $pengeluaranMurni = Spj::where('jenis_bos', $jenis)
                ->whereYear('tanggal_transaksi', $currentYear)
                ->where('jenis_transaksi', 'Pengeluaran')
                ->where(function($q) {
                    $q->where('uraian', 'not like', '%Tarik Tunai%')
                      ->where('uraian', 'not like', '%Pengambilan Tunai%')
                      ->where('uraian', 'not like', '%Pindahan Kas%')
                      ->where('uraian', 'not like', '%Pajak Bunga%')
                      ->where('uraian', 'not like', '%Bunga Bank%');
                })
                ->sum('nominal');

            // C. Perhitungan Saldo Kas Akhir BKU (Persis Baris Paling Terakhir BKU)
            $totalSemuaPenerimaanBku = Spj::where('jenis_bos', $jenis)
                ->whereYear('tanggal_transaksi', $currentYear)
                ->where('jenis_transaksi', 'Penerimaan')
                ->sum('nominal');

            $totalSemuaPengeluaranBku = Spj::where('jenis_bos', $jenis)
                ->whereYear('tanggal_transaksi', $currentYear)
                ->where('jenis_transaksi', 'Pengeluaran')
                ->sum('nominal');

            $saldoKasBarisTerakhir = $totalSemuaPenerimaanBku - $totalSemuaPengeluaranBku;
            $totalSaldoSeluruhnya += $saldoKasBarisTerakhir;

            $ringkasanBos[$jenis] = [
                'penerimaan'      => $paguDiterima,          // Total Pagu Diterima
                'pengeluaran'     => $pengeluaranMurni,      // Total Pembelanjaan Murni
                'saldo'           => $saldoKasBarisTerakhir, // Saldo Baris Terakhir BKU
                'total_transaksi' => Spj::where('jenis_bos', $jenis)->whereYear('tanggal_transaksi', $currentYear)->count()
            ];
        }

        // 4. Total Transaksi SPJ Tahun Berjalan
        $totalTransaksiBosp = Spj::whereYear('tanggal_transaksi', $currentYear)->count();

        return view('dashboard', compact(
            'currentYear',
            'latestMonthNumber',
            'latestMonthName',
            'progressPercentage',
            'completedYears',
            'ringkasanBos',
            'totalSaldoSeluruhnya',
            'totalTransaksiBosp'
        ));
    }
}