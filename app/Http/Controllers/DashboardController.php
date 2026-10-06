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

        // 2. Tahun BOSP Selesai (Diambil dari Dokumen BOSP yang sudah di-upload)
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
            $penerimaan = Spj::where('jenis_bos', $jenis)
                ->whereYear('tanggal_transaksi', $currentYear)
                ->where('jenis_transaksi', 'Penerimaan')
                ->sum('nominal');

            $pengeluaran = Spj::where('jenis_bos', $jenis)
                ->whereYear('tanggal_transaksi', $currentYear)
                ->where('jenis_transaksi', 'Pengeluaran')
                ->sum('nominal');

            $saldo = $penerimaan - $pengeluaran;
            $totalSaldoSeluruhnya += $saldo;

            $ringkasanBos[$jenis] = [
                'penerimaan' => $penerimaan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $saldo,
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