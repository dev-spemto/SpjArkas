<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi A2 Dinas - {{ $spj->no_bkp ?? 'SPJ' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            background: #e9ecef;
        }
        .a2-paper {
            max-width: 950px;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        .table-a2 {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }
        .table-a2 td, .table-a2 th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        .no-border-bottom { border-bottom: none !important; }
        .no-border-top { border-top: none !important; }
        .no-border-left { border-left: none !important; }
        .no-border-right { border-right: none !important; }
        
        .box-no-bukti {
            border: 1px solid #000;
            padding: 2px 8px;
            display: inline-block;
            font-weight: bold;
            background-color: #fff;
        }
        .big-a2 {
            font-size: 32px;
            font-weight: 900;
            line-height: 1;
            display: block;
        }

        @media print {
            body { background: #fff; font-size: 10pt; }
            .no-print { display: none !important; }
            .a2-paper {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Control Bar Atas -->
<div class="container my-3 no-print text-center">
    <button onclick="window.print()" class="btn btn-primary btn-sm px-4 me-2">
        <i class="bi bi-printer me-1"></i> Cetak / Export PDF
    </button>
    <a href="{{ route('spjs.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<div class="a2-paper">
    <table class="table-a2">
        <!-- HEADER KANTOR / DINAS -->
        <tr>
            <td style="width: 12%; text-align: center; vertical-align: middle;" class="no-border-right">
                @if($schoolProfile && $schoolProfile->logo)
                    <img src="{{ asset('storage/' . $schoolProfile->logo) }}" style="max-height: 55px; max-width: 55px;">
                @else
                    <div style="font-size: 9px; font-weight: bold;">[LOGO SEKOLAH/PEMKAB]</div>
                @endif
            </td>
            <td style="width: 53%;" class="no-border-left">
                <div class="fw-bold text-uppercase text-center mb-1" style="font-size: 12px;">PEMERINTAH KABUPATEN BREBES</div>
                <table class="w-100 border-0" style="font-size: 11px;">
                    <tr>
                        <td style="width: 32%; border:none; padding: 1px;">KANTOR / DINAS</td>
                        <td style="width: 3%; border:none; padding: 1px;">:</td>
                        <td style="border:none; padding: 1px;" class="fw-bold text-uppercase">{{ $schoolProfile->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 1px;">TAHUN ANGGARAN</td>
                        <td style="border:none; padding: 1px;">:</td>
                        <td style="border:none; padding: 1px;">{{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('Y') : date('Y') }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 1px;">NOMOR</td>
                        <td style="border:none; padding: 1px;">:</td>
                        <td style="border:none; padding: 1px;">
                            <span class="box-no-bukti">{{ $spj->no_bkp ?? '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 35%; text-align: right; vertical-align: top;" class="bg-light">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="small text-start ps-1" style="font-size: 10px;">Lembar ke I / II / III / IV</span>
                    <span class="big-a2 me-2">A2</span>
                </div>
            </td>
        </tr>

        <!-- ISI TANDA BUKTI PENGELUARAN & KETERANGAN -->
        <tr>
            <!-- Kolom Kiri: Tanda Bukti Pengeluaran -->
            <td colspan="2" style="padding: 10px; height: 260px;">
                <div class="text-center fw-bold fs-6 mb-3 text-uppercase">TANDA BUKTI PENGELUARAN</div>
                
                <table class="w-100 border-0" style="font-size: 11px;">
                    <tr>
                        <td style="width: 25%; border:none; padding: 4px 0;">Sudah terima dari</td>
                        <td style="width: 3%; border:none; padding: 4px 0;">:</td>
                        <td style="border:none; padding: 4px 0;" class="fw-bold text-uppercase">{{ $schoolProfile->nama_sekolah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 4px 0;">Uang Sejumlah</td>
                        <td style="border:none; padding: 4px 0;">:</td>
                        <td style="border:none; padding: 4px 0;">
                            Rp <span class="ms-4 fw-bold" style="font-size: 13px;">{{ number_format($spj->nominal, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 6px 0;">Terbilang</td>
                        <td style="border:none; padding: 6px 0;">:</td>
                        <td style="border:none; padding: 6px 0;" class="fw-bold fst-italic text-center">
                            === {{ $terbilang }} ===
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 4px 0;">Yaitu untuk Pembayaran</td>
                        <td style="border:none; padding: 4px 0;">:</td>
                        <td style="border:none; padding: 4px 0;">{{ $spj->uraian }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 4px 0;">Untuk Pek./Kegiatan</td>
                        <td style="border:none; padding: 4px 0;">:</td>
                        <td style="border:none; padding: 4px 0;">
                            {{ $spj->kode_kegiatan ? $spj->kode_kegiatan . ' - ' . ($spj->nama_kegiatan ?? '') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 4px 0;">Kode Rekening</td>
                        <td style="border:none; padding: 4px 0;">:</td>
                        <td style="border:none; padding: 4px 0;">
                            {{ $spj->kode_rekening ? $spj->kode_rekening . ' - ' . ($spj->nama_rekening ?? '') : '-' }}
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Kolom Kanan: Keterangan & Rincian Potongan -->
            <td style="padding: 8px;">
                <div class="text-center fw-bold mb-2">KETERANGAN</div>
                <div style="font-size: 10px; line-height: 1.3;" class="mb-2">
                    Barang-barang dimaksud telah dibukukan ke buku persediaan/inventaris pada tanggal:<br>
                    <strong class="d-block mt-1">{{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('d F Y') : '-' }}</strong>
                </div>
                
                <hr class="my-1" style="border-top: 1px solid #000;">
                
                <table class="w-100 border-0" style="font-size: 10px;">
                    <tr>
                        <td style="border:none; padding: 1px;">Jumlah Kotor</td>
                        <td style="border:none; padding: 1px;">Rp.</td>
                        <td style="border:none; padding: 1px;" class="text-end fw-bold">{{ number_format($spj->nominal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 1px;">Potongan</td>
                        <td style="border:none; padding: 1px;">Rp.</td>
                        <td style="border:none; padding: 1px;" class="text-end">-</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 1px;" class="fw-bold">Dibayarkan</td>
                        <td style="border:none; padding: 1px;" class="fw-bold">Rp.</td>
                        <td style="border:none; padding: 1px;" class="text-end fw-bold">{{ number_format($spj->nominal, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="mt-2 fw-bold" style="font-size: 10px;">Perincian Potongan</div>
                <ol class="ps-3 mb-1" style="font-size: 10px;">
                    <li>-</li>
                    <li>-</li>
                    <li>-</li>
                    <li>-</li>
                </ol>

                <div style="font-size: 9px; font-style: italic;" class="mt-3 text-muted">
                    Pengeluaran/pembelian dilakukan berdasarkan penganggaran RKASP yang disesuaikan kebutuhan
                </div>
            </td>
        </tr>

        <!-- TANDA TANGAN (4 KOLOM) -->
        <tr>
            <td colspan="3" class="p-0">
                <table class="w-100 text-center border-0">
                    <tr>
                        <td style="width: 25%; border:none; border-right: 1px solid #000; padding: 8px 4px; vertical-align: top;">
                            <div style="font-size: 10px;">
                                Yang menerima barang/<br>memeriksa pekerjaan tersebut di atas
                            </div>
                            <div style="height: 50px;"></div>
                            <strong class="text-uppercase"><u>{{ $spj->penerima_toko ?? '...................................' }}</u></strong>
                        </td>

                        <td style="width: 25%; border:none; border-right: 1px solid #000; padding: 8px 4px; vertical-align: top;">
                            <div style="font-size: 10px;">
                                Setuju dibayarkan<br>Kepala Sekolah
                            </div>
                            <div style="height: 50px;"></div>
                            <strong class="text-uppercase"><u>{{ $schoolProfile->nama_kepala_sekolah ?? '...................................' }}</u></strong><br>
                            <span style="font-size: 10px;">NIP. {{ $schoolProfile->nip_kepala_sekolah ?? '-' }}</span>
                        </td>

                        <td style="width: 25%; border:none; border-right: 1px solid #000; padding: 8px 4px; vertical-align: top;">
                            <div style="font-size: 10px;">
                                Lunas Di Bayar, {{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('d F Y') : '................' }}<br>
                                Yang membayarkan<br>Bendahara
                            </div>
                            <div style="height: 50px;"></div>
                            <strong class="text-uppercase"><u>{{ $schoolProfile->nama_bendahara ?? '...................................' }}</u></strong><br>
                            <span style="font-size: 10px;">NIP. {{ $schoolProfile->nip_bendahara ?? '-' }}</span>
                        </td>

                        <td style="width: 25%; border:none; padding: 8px 4px; vertical-align: top;">
                            <div style="font-size: 10px;">
                                Pejabat Pelaksana Teknis
                            </div>
                            <div style="height: 62px;"></div>
                            <strong class="text-uppercase"><u>...................................</u></strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

</body>
</html>