<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi A2 BPU - {{ $spj->no_bkp ?? 'SPJ' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; background: #fff; }
        .wrapper { max-width: 800px; margin: 0 auto; padding: 15px; border: 1px solid #ccc; background: #fff; }
        .table-borderless td, .table-borderless th { border: none !important; padding: 2px 4px; }
        .border-bottom-double { border-bottom: 3px double #000 !important; }
        .box-nominal { border: 2px solid #000; padding: 5px 12px; font-weight: bold; font-size: 15px; display: inline-block; }
        .dashed-line { border-top: 1px dashed #000; margin: 20px 0; }
        @media print {
            .no-print { display: none !important; }
            .wrapper { border: none; padding: 0; max-width: 100%; }
            body { font-size: 11pt; }
        }
    </style>
</head>
<body>

<div class="container my-3 no-print text-center">
    <button onclick="window.print()" class="btn btn-primary px-4 me-2">
        <i class="bi bi-printer"></i> Cetak / Simpan PDF
    </button>
    <a href="{{ route('spjs.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="wrapper">
    <!-- BAGIAN 1: KWITANSI A2 -->
    <table class="w-100 border-bottom-double mb-2 pb-1">
        <tr>
            @if($schoolProfile && $schoolProfile->logo)
                <td style="width: 12%; text-align: center; vertical-align: middle;">
                    <img src="{{ asset('storage/' . $schoolProfile->logo) }}" style="max-height: 65px; max-width: 65px;">
                </td>
            @endif
            <td class="text-center" style="vertical-align: middle;">
                <h6 class="mb-0 fw-bold text-uppercase">{{ $schoolProfile->nama_sekolah ?? 'NAMA SEKOLAH BELUM DIATUR' }}</h6>
                <p class="mb-0 small">{{ $schoolProfile->alamat ?? '' }} {{ $schoolProfile->kecamatan ? 'Kec. ' . $schoolProfile->kecamatan : '' }} {{ $schoolProfile->kabupaten_kota ? 'Kab. ' . $schoolProfile->kabupaten_kota : '' }}</p>
                <small class="text-muted">NPSN: {{ $schoolProfile->npsn ?? '-' }}</small>
            </td>
        </tr>
    </table>

    <div class="text-center mb-2">
        <h6 class="fw-bold text-uppercase mb-0" style="text-decoration: underline;">KWITANSI / BUKTI PEMBAYARAN (A2)</h6>
        <small>Tahun Anggaran: {{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('Y') : date('Y') }}</small>
    </div>

    <table class="table table-borderless mb-1">
        <tr>
            <td style="width: 18%;">No. BKP</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;" class="fw-bold">{{ $spj->no_bkp ?? '-' }}</td>
            <td style="width: 18%;">Kode Kegiatan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $spj->kode_kegiatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis BOSP</td>
            <td>:</td>
            <td>{{ $spj->jenis_bos }}</td>
            <td>Kode Rekening</td>
            <td>:</td>
            <td>{{ $spj->kode_rekening ?? '-' }}</td>
        </tr>
    </table>

    <table class="table table-borderless my-2">
        <tr>
            <td style="width: 22%; font-weight: bold;">Sudah Diterima Dari</td>
            <td style="width: 2%;">:</td>
            <td>{{ $schoolProfile->redaksi_diterima ?? 'Bendahara Bantuan Operasional Sekolah' }} {{ $schoolProfile->nama_sekolah ?? '' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Uang Sejumlah</td>
            <td>:</td>
            <td class="fst-italic fw-bold text-capitalize" style="background-color: #f0f0f0; padding: 3px 6px;">
                === {{ $terbilang }} ===
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Untuk Pembayaran</td>
            <td>:</td>
            <td>{{ $spj->uraian }}</td>
        </tr>
    </table>

    <div class="my-2 d-flex justify-content-between align-items-center">
        <span class="box-nominal">Rp {{ number_format($spj->nominal, 0, ',', '.') }}</span>
    </div>

    <table class="w-100 text-center mt-3">
        <tr>
            <td style="width: 33%;">
                Setuju Dibayar:<br><strong>Kepala Sekolah</strong>
                <br><br><br>
                <u><strong>{{ $schoolProfile->nama_kepala_sekolah ?? '..............................' }}</strong></u><br>
                NIP. {{ $schoolProfile->nip_kepala_sekolah ?? '-' }}
            </td>
            <td style="width: 33%;">
                Lunas Dibayar Tgl: {{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('d/m/Y') : '............' }}<br><strong>Bendahara Sekolah</strong>
                <br><br><br>
                <u><strong>{{ $schoolProfile->nama_bendahara ?? '..............................' }}</strong></u><br>
                NIP. {{ $schoolProfile->nip_bendahara ?? '-' }}
            </td>
            <td style="width: 34%;">
                {{ $schoolProfile->kabupaten_kota ?? 'Brebes' }}, {{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('d F Y') : '................' }}<br><strong>Penerima / Penyedia</strong>
                <br><br><br>
                <u><strong>{{ $spj->penerima_toko ?? '..............................' }}</strong></u>
            </td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    <!-- BAGIAN 2: BUKTI PENERIMAAN UANG (BPU) -->
    <div class="text-center mb-2">
        <h6 class="fw-bold text-uppercase mb-0" style="text-decoration: underline;">BUKTI PENERIMAAN UANG (BPU)</h6>
        <small>Lampiran BKP No: {{ $spj->no_bkp ?? '-' }}</small>
    </div>

    <table class="table table-borderless my-2">
        <tr>
            <td style="width: 22%; font-weight: bold;">Telah Diterima Dari</td>
            <td style="width: 2%;">:</td>
            <td>{{ $schoolProfile->nama_bendahara ?? 'Bendahara Sekolah' }} (Bendahara {{ $schoolProfile->nama_sekolah ?? '' }})</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Uang Sejumlah</td>
            <td>:</td>
            <td class="fw-bold">Rp {{ number_format($spj->nominal, 0, ',', '.') }} (<i>{{ $terbilang }}</i>)</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Untuk Keperluan</td>
            <td>:</td>
            <td>{{ $spj->uraian }}</td>
        </tr>
    </table>

    <table class="w-100 text-center mt-3">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br><strong>Bendahara Sekolah</strong>
                <br><br><br>
                <u><strong>{{ $schoolProfile->nama_bendahara ?? '..............................' }}</strong></u>
            </td>
            <td style="width: 50%;">
                {{ $schoolProfile->kabupaten_kota ?? 'Brebes' }}, {{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('d F Y') : '................' }}<br><strong>Yang Menerima</strong>
                <br><br><br>
                <u><strong>{{ $spj->penerima_toko ?? '..............................' }}</strong></u>
            </td>
        </tr>
    </table>
</div>

</body>
</html>