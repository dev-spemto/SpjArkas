<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bundle Kwitansi SPJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000; background: #e9ecef; }
        
        .a2-paper {
            max-width: 920px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            box-sizing: border-box;
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
        .no-border-right { border-right: none !important; }
        .no-border-left { border-left: none !important; }
        
        .box-no-bukti {
            border: 1px solid #000;
            padding: 1px 6px;
            display: inline-block;
            font-weight: bold;
            background-color: #fff;
        }
        .big-a2 {
            font-size: 34px;
            font-weight: 900;
            line-height: 1;
            display: block;
        }

        /* PROPORSI KWITANSI A2 PANJANG / STANDAR (PIPIH) */
        .layout-a2-panjang .content-td { height: 160px !important; }
        .layout-a2-panjang .ttd-td { height: 130px !important; }

        /* PROPORSI KWITANSI A2 BPU SAMA (KOTAK / TALLER) */
        .layout-a2-kotak .content-td { height: 260px !important; }
        .layout-a2-kotak .ttd-td { height: 140px !important; }

        /* --- STYLING PRESISI KWITANSI UMUM SEKOLAH (TIMES NEW ROMAN) --- */
        .kwitansi-umum-card {
            max-width: 720px;
            margin: 0 auto;
            border: 2px solid #000;
            background: #fff;
            display: flex;
            flex-direction: row;
            width: 100%;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .kwitansi-umum-card * {
            font-family: 'Times New Roman', Times, serif !important;
        }
        .kwitansi-sidebar {
            width: 65px;
            background-color: #a3b88c;
            border-right: 2px solid #000;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 10px 3px;
            box-sizing: border-box;
            gap: 4px;
        }
        .sidebar-col-1, .sidebar-col-2 {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            text-align: center;
        }
        .sidebar-col-1 span {
            font-size: 13.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }
        .sidebar-col-2 span {
            font-size: 8.5px;
            font-style: italic;
            display: block;
        }
        .kwitansi-umum-main {
            flex: 1;
            padding: 12px 18px;
        }
        .banner-highlight {
            background-color: #c2d69b;
            border: 1px solid #7a9252;
            padding: 3px 8px;
            font-size: 11px;
            font-style: italic;
            font-weight: bold;
            margin-bottom: 12px;
        }

        /* CSS KHUSUS PRINT / EXPORT PDF */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body { 
                background: #fff; 
                font-size: 9pt; 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .a2-paper {
                box-shadow: none;
                margin: 0 0 15mm 0;
                padding: 0;
                width: 100% !important;
                max-width: 100% !important;
                page-break-after: always;
            }
            .a2-paper:last-child {
                page-break-after: avoid;
            }

            .layout-a2-panjang .content-td { height: 150px !important; }
            .layout-a2-panjang .ttd-td { height: 125px !important; }

            .layout-a2-kotak .content-td { height: 260px !important; }
            .layout-a2-kotak .ttd-td { height: 135px !important; }

            .kwitansi-sidebar {
                background-color: #a3b88c !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .banner-highlight {
                background-color: #c2d69b !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<!-- Control Bar Atas (Non-Print) -->
<div class="container my-3 no-print">
    <div class="card shadow-sm p-3 bg-white border-0">
        <div class="row align-items-center g-2">
            <div class="col-md-5">
                <label class="form-label fw-bold mb-1"><i class="bi bi-sliders me-1"></i>Pilih Format Layout Kwitansi Bundle:</label>
                <select id="typeSelector" class="form-select form-select-sm fw-semibold text-primary" onchange="changeFormat(this.value)">
                    <option value="a2" {{ $type == 'a2' ? 'selected' : '' }}>1. KWITANSI A2 (Panjang / Standar)</option>
                    <option value="a2_bpu" {{ $type == 'a2_bpu' ? 'selected' : '' }}>2. KWITANSI A2 BPU SAMA (Kotak / Gabungan)</option>
                    <option value="umum" {{ $type == 'umum' ? 'selected' : '' }}>3. KWITANSI UMUM SEKOLAH</option>
                    <option value="umum_bpu" {{ $type == 'umum_bpu' ? 'selected' : '' }}>4. KWITANSI UMUM SEKOLAH BPU SAMA</option>
                </select>
            </div>
            <div class="col-md-7 text-end pt-3 pt-md-0">
                <button onclick="window.print()" class="btn btn-primary btn-sm px-4 me-2">
                    <i class="bi bi-printer me-1"></i> Cetak / Export ke 1 PDF
                </button>
                <a href="{{ route('spjs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@php
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Deteksi & Muat Logo Sekolah
    $logoSrc = null;
    if ($schoolProfile && !empty($schoolProfile->logo)) {
        $cleanLogo = ltrim(str_replace('storage/', '', $schoolProfile->logo), '/');
        $possiblePaths = [
            storage_path('app/public/' . $cleanLogo),
            public_path('storage/' . $cleanLogo),
            public_path($schoolProfile->logo),
            storage_path('app/' . $schoolProfile->logo),
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path) && !is_dir($path)) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $logoSrc = 'data:image/' . ($ext ?: 'png') . ';base64,' . base64_encode(file_get_contents($path));
                break;
            }
        }

        if (!$logoSrc) {
            $logoSrc = asset('storage/' . $cleanLogo);
        }
    }
@endphp

<!-- Loop Cetak Transaksi -->
@foreach($spjs as $spj)
    @php
        $tgl = $spj->tanggal_transaksi;
        $tglFormated = $tgl ? $tgl->format('d') . ' ' . $bulanIndo[(int)$tgl->format('m')] . ' ' . $tgl->format('Y') : '................';
        $namaKecamatan = $schoolProfile->kecamatan ?? 'Tonjong';
        
        // Bersihkan tanda === untuk Kwitansi Umum
        $terbilangUmum = trim(str_replace('===', '', $spj->terbilang_text));
    @endphp

    <div class="a2-paper {{ $type == 'a2' ? 'layout-a2-panjang' : ($type == 'a2_bpu' ? 'layout-a2-kotak' : '') }}">
        @if($type == 'a2' || $type == 'a2_bpu')
            <!-- FORMAT KWITANSI A2 STANDAR DINAS PEMKAB BREBES -->
            <table class="table-a2">
                <tr>
                    <td style="width: 12%; text-align: center; vertical-align: middle;" class="no-border-right">
                        @if($logoSrc)
                            <img src="{{ $logoSrc }}" style="max-height: 55px; max-width: 55px; object-fit: contain;">
                        @else
                            <div style="font-size: 8px; font-weight: bold; color: #666;">[LOGO]</div>
                        @endif
                    </td>
                    <td style="width: 53%;" class="no-border-left">
                        <div class="fw-bold text-uppercase text-center mb-1" style="font-size: 11px;">PEMERINTAH KABUPATEN BREBES</div>
                        <table class="w-100 border-0" style="font-size: 10.5px;">
                            <tr>
                                <td style="width: 32%; border:none; padding: 1px; white-space: nowrap;">KANTOR / DINAS</td>
                                <td style="width: 3%; border:none; padding: 1px;">:</td>
                                <td style="border:none; padding: 1px;" class="fw-bold text-uppercase">{{ $schoolProfile->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 1px; white-space: nowrap;">TAHUN ANGGARAN</td>
                                <td style="border:none; padding: 1px;">:</td>
                                <td style="border:none; padding: 1px;">{{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('Y') : date('Y') }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 1px; white-space: nowrap;">NOMOR</td>
                                <td style="border:none; padding: 1px;">:</td>
                                <td style="border:none; padding: 1px;">
                                    <span class="box-no-bukti">{{ $spj->no_bkp ?? '-' }}</span>
                                    @if($type == 'a2_bpu')
                                        <span class="ms-2 fw-normal" style="font-size: 10px;">{{ $spj->jumlah_item ?? 0 }}( Belanja/Barang/kegiatan )</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 35%; text-align: right; vertical-align: top;">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="small text-start ps-1" style="font-size: 9.5px;">Lembar ke I / II / III / IV</span>
                            <span class="big-a2 me-2">A2</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <!-- Kolom Kiri: Tanda Bukti Pengeluaran -->
                    <td colspan="2" class="content-td" style="padding: 8px;">
                        <div class="text-center fw-bold fs-6 mb-2 text-uppercase">TANDA BUKTI PENGELUARAN</div>
                        
                        <table class="w-100 border-0" style="font-size: 10.5px;">
                            <tr>
                                <td style="width: 28%; border:none; padding: 3px 0; white-space: nowrap;">Sudah terima dari</td>
                                <td style="width: 2%; border:none; padding: 3px 0;">:</td>
                                <td style="border:none; padding: 3px 0;" class="fw-bold text-uppercase">
                                    <span style="font-size: 7.5px;">BENDAHARA BANTUAN OPERASIONAL SEKOLAH {{ $schoolProfile->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 4px 0; white-space: nowrap;">Uang Sejumlah</td>
                                <td style="border:none; padding: 4px 0;">:</td>
                                <td style="border:none; padding: 4px 0;">
                                    <div class="d-flex align-items-end w-100 border-bottom border-dark pb-1" style="min-height: 20px;">
                                        <span class="fw-bold">Rp</span>
                                        <span class="ms-auto fw-bold" style="font-size: 12.5px;">{{ number_format($spj->nominal, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 4px 0; white-space: nowrap;">Terbilang</td>
                                <td style="border:none; padding: 4px 0;">:</td>
                                <td style="border:none; padding: 4px 0;" class="fw-bold fst-italic {{ $type == 'a2' ? 'text-center' : '' }}">
                                    === {{ $spj->terbilang_text }} ===
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 3px 0; white-space: nowrap;">Yaitu untuk Pembayaran</td>
                                <td style="border:none; padding: 3px 0;">:</td>
                                <td style="border:none; padding: 3px 0;" class="fw-semibold">
                                    @if($type == 'a2' && empty($spj->uraian))
                                        (terlampir)
                                    @else
                                        {{ $spj->uraian }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 3px 0; white-space: nowrap;">Untuk Pek./Kegiatan</td>
                                <td style="border:none; padding: 3px 0;">:</td>
                                <td style="border:none; padding: 3px 0;">
                                    {{ $spj->kegiatan_combined }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border:none; padding: 3px 0; white-space: nowrap;">Kode Rekening</td>
                                <td style="border:none; padding: 3px 0;">:</td>
                                <td style="border:none; padding: 3px 0;">
                                    {{ $spj->rekening_combined }}
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- Kolom Kanan: Keterangan & Potongan -->
                    <td class="content-td" style="padding: 6px;">
                        <div class="text-center fw-bold mb-1">KETERANGAN</div>
                        <div style="font-size: 9.5px; line-height: 1.2;" class="mb-2">
                            Barang-barang dimaksud telah dibukukan ke buku persediaan/inventaris pada tanggal:<br>
                            <strong class="d-block mt-1">{{ $tglFormated }}</strong>
                        </div>
                        
                        <hr class="my-1" style="border-top: 1px solid #000;">
                        
                        <table class="w-100 border-0" style="font-size: 9.5px;">
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

                        <div class="mt-1 fw-bold" style="font-size: 9.5px;">Perincian Potongan</div>
                        <ol class="ps-3 mb-1" style="font-size: 9.5px; margin-bottom: 2px;">
                            <li>-</li>
                            <li>-</li>
                            <li>-</li>
                            <li>-</li>
                        </ol>

                        <div style="font-size: 8px; font-style: italic; line-height: 1.1;" class="mt-2">
                            Pengeluaran/pembelian dilakukan berdasarkan penganggaran RKASP yang disesuaikan kebutuhan
                        </div>
                    </td>
                </tr>

                <!-- TANDA TANGAN 4 KOLOM PRESISI -->
                <tr>
                    <td colspan="3" class="p-0">
                        <table class="w-100 text-center border-0" style="table-layout: fixed;">
                            <tr>
                                <td class="ttd-td" style="width: 25%; border:none; border-right: 1px solid #000; padding: 6px 4px; vertical-align: top;">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div style="font-size: 9.5px; line-height: 1.3;">
                                            <div style="visibility: hidden;">&nbsp;</div>
                                            <div>Yang menerima barang/</div>
                                            <div>memeriksa pekerjaan tersebut di atas</div>
                                        </div>
                                        <div style="margin-top: 40px;">
                                            <strong class="text-uppercase"><u>{{ $spj->penerima_toko ?? '...................................' }}</u></strong>
                                            <div style="visibility: hidden; font-size: 9.5px; line-height: 1.2;">NIP. -</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="ttd-td" style="width: 25%; border:none; border-right: 1px solid #000; padding: 6px 4px; vertical-align: top;">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div style="font-size: 9.5px; line-height: 1.3;">
                                            <div style="visibility: hidden;">&nbsp;</div>
                                            <div>Setuju dibayarkan</div>
                                            <div>Kepala Sekolah</div>
                                        </div>
                                        <div style="margin-top: 40px;">
                                            <strong class="text-uppercase"><u>{{ $schoolProfile->nama_kepala_sekolah ?? '...................................' }}</u></strong><br>
                                            <span style="font-size: 9.5px; line-height: 1.2;">NIP. {{ $schoolProfile->nip_kepala_sekolah ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="ttd-td" style="width: 25%; border:none; border-right: 1px solid #000; padding: 6px 4px; vertical-align: top;">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div style="font-size: 9.5px; line-height: 1.3;">
                                            <div>Lunas Di Bayar, {{ $tglFormated }}</div>
                                            <div>Yang membayarkan</div>
                                            <div>Bendahara</div>
                                        </div>
                                        <div style="margin-top: 40px;">
                                            <strong class="text-uppercase"><u>{{ $schoolProfile->nama_bendahara ?? '...................................' }}</u></strong><br>
                                            <span style="font-size: 9.5px; line-height: 1.2;">NIP. {{ $schoolProfile->nip_bendahara ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="ttd-td" style="width: 25%; border:none; padding: 6px 4px; vertical-align: top;">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div style="font-size: 9.5px; line-height: 1.3;">
                                            <div style="visibility: hidden;">&nbsp;</div>
                                            <div>Pejabat Pelaksana Teknis</div>
                                            <div style="visibility: hidden;">&nbsp;</div>
                                        </div>
                                        <div style="margin-top: 40px;">
                                            <strong><u>...................................</u></strong>
                                            <div style="visibility: hidden; font-size: 9.5px; line-height: 1.2;">NIP. -</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        @else
            <!-- FORMAT KWITANSI UMUM SEKOLAH -->
            <div class="kwitansi-umum-card">
                <!-- Sidebar Hijau Kiri: 2 Kolom Vertikal Sejajar -->
                <div class="kwitansi-sidebar">
                    <div class="sidebar-col-1">
                        <span>{{ $schoolProfile->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</span>
                    </div>
                    <div class="sidebar-col-2">
                        <span>Alamat : {{ $schoolProfile->alamat ?? 'Jalan Raya Linggapura No. 46' }} Email: {{ $schoolProfile->email ?? 'smpmuhtonjong@gmail.com' }} Akreditasi : A</span>
                    </div>
                </div>

                <!-- Area Utama Kwitansi -->
                <div class="kwitansi-umum-main">
                    <!-- Judul Utama -->
                    <div class="text-center mb-2">
                        <h3 class="fw-bold mb-0" style="text-decoration: underline; letter-spacing: 5px;">K W I T A N S I</h3>
                    </div>

                    <!-- Sub-Header Bar -->
                    <div class="d-flex justify-content-between align-items-center fw-bold mb-2" style="font-size: 11px;">
                        <div>
                            Nomor : <u>{{ $spj->no_bkp ?? '-' }}</u>
                            @if($type == 'umum_bpu')
                                <span class="fw-normal">({{ $spj->jumlah_item ?? 0 }} Belanja)</span>
                            @endif
                        </div>
                        <div>
                            Kode Rekening : <u>{{ $spj->kode_rekening ?? ($spj->rekening_combined ?? '-') }}</u>
                        </div>
                        <div>
                            Tahun Anggaran : <u>{{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('Y') : date('Y') }}</u>
                        </div>
                    </div>

                    <!-- Banner Highlight Hijau -->
                    <div class="banner-highlight">
                        {{ $spj->penerima_toko ? $spj->penerima_toko . ' (' . $spj->uraian . ')' : $spj->uraian }}
                    </div>

                    <!-- Detail Rincian -->
                    <table class="w-100 mb-2" style="font-size: 11px; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25%; padding: 4px 0; white-space: nowrap;" class="fw-bold">Telah Terima dari</td>
                            <td style="width: 2%; padding: 4px 0;" class="fw-bold">:</td>
                            <td style="padding: 4px 0;" class="fw-bold text-uppercase">
                                <span style="font-size: 7.5px;">BENDAHARA BANTUAN OPERASIONAL SEKOLAH {{ $schoolProfile->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; white-space: nowrap;" class="fw-bold">Uang Sebanyak</td>
                            <td style="padding: 4px 0;" class="fw-bold">:</td>
                            <td style="padding: 4px 0;">
                                <div class="d-flex align-items-baseline w-100">
                                    <span class="fw-bold fst-italic">Rp</span>
                                    <span class="ms-auto fw-bold fst-italic" style="font-size: 13px;">{{ number_format($spj->nominal, 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; white-space: nowrap;" class="fw-bold">Yaitu Pembayaran</td>
                            <td style="padding: 4px 0;" class="fw-bold">:</td>
                            <td style="padding: 4px 0;">{{ $spj->uraian }}</td>
                        </tr>
                    </table>

                    <!-- Terbilang Bar Dengan Garis Hitam Dibawahnya -->
                    <div class="border-bottom border-2 border-dark pb-1 mb-3 mt-2" style="font-size: 11px;">
                        <span class="fw-bold me-2">Terbilang :</span>
                        <span class="fw-bold fst-italic ms-2" style="font-size: 12px;">{{ $terbilangUmum }}</span>
                    </div>

                    <!-- Tanda Tangan (3 Kolom Presisi Rapat) -->
                    <table class="w-100 text-center" style="font-size: 10.5px; table-layout: fixed;">
                        <tr>
                            <td style="width: 33%; vertical-align: top;">
                                <div>Mengetahui :</div>
                                <div>Kepala {{ $schoolProfile->nama_sekolah ?? 'SMP MUHAMMADIYAH TONJONG' }},</div>
                                <div style="height: 50px;"></div>
                                <strong class="text-uppercase"><u>{{ $schoolProfile->nama_kepala_sekolah ?? '...................................' }}</u></strong><br>
                                <span>NIP. {{ $schoolProfile->nip_kepala_sekolah ?? '-' }}</span>
                            </td>

                            <td style="width: 33%; vertical-align: top;">
                                <div>Telah dibayar lunas</div>
                                <div>Bendahara,</div>
                                <div style="height: 50px;"></div>
                                <strong class="text-uppercase"><u>{{ $schoolProfile->nama_bendahara ?? '...................................' }}</u></strong><br>
                                <span>NIP. {{ $schoolProfile->nip_bendahara ?? '-' }}</span>
                            </td>

                            <td style="width: 34%; vertical-align: top;">
                                <div>{{ $namaKecamatan }}, {{ $tglFormated }}</div>
                                <div>Penerima,</div>
                                <div style="height: 50px;"></div>
                                <strong class="text-uppercase"><u>{{ $spj->penerima_toko ?? '...................................' }}</u></strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endforeach

<script>
    function changeFormat(newType) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('type', newType);
        window.location.search = urlParams.toString();
    }
</script>

</body>
</html>