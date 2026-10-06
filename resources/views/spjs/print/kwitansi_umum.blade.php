<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Umum - {{ $spj->no_bkp ?? 'SPJ' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 13px; color: #000; background: #fff; }
        .wrapper { max-width: 800px; margin: 0 auto; padding: 20px; border: 2px solid #333; background: #fff; }
        .border-bottom-thick { border-bottom: 2px solid #000 !important; }
        .box-nominal { border: 2px solid #000; padding: 8px 18px; font-weight: bold; font-size: 18px; background-color: #f9f9f9; display: inline-block; }
        @media print {
            .no-print { display: none !important; }
            .wrapper { border: 2px solid #000; padding: 15px; max-width: 100%; }
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
    <!-- Header Kop Sekolah -->
    <table class="w-100 border-bottom-thick mb-3 pb-2">
        <tr>
            @if($schoolProfile && $schoolProfile->logo)
                <td style="width: 15%; text-align: center; vertical-align: middle;">
                    <img src="{{ asset('storage/' . $schoolProfile->logo) }}" style="max-height: 75px; max-width: 75px;">
                </td>
            @endif
            <td class="text-center" style="vertical-align: middle;">
                <h4 class="mb-0 fw-bold text-uppercase">{{ $schoolProfile->nama_sekolah ?? 'NAMA SEKOLAH' }}</h4>
                <p class="mb-0 small">{{ $schoolProfile->alamat ?? '' }} {{ $schoolProfile->kecamatan ? 'Kec. ' . $schoolProfile->kecamatan : '' }} {{ $schoolProfile->kabupaten_kota ? 'Kab. ' . $schoolProfile->kabupaten_kota : '' }}</p>
            </td>
        </tr>
    </table>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-uppercase mb-0" style="text-decoration: underline;">KWITANSI BUKTI PEMBAYARAN</h5>
        <span class="badge bg-outline-dark border text-dark">No: {{ $spj->no_bkp ?? '-' }}</span>
    </div>

    <table class="table table-borderless my-3">
        <tr>
            <td style="width: 25%; font-weight: bold;">Telah Diterima Dari</td>
            <td style="width: 2%;">:</td>
            <td>{{ $schoolProfile->nama_sekolah ?? '' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Uang Sejumlah</td>
            <td>:</td>
            <td class="fst-italic fw-bold text-capitalize" style="background-color: #f0f0f0; padding: 6px;">
                === {{ $terbilang }} ===
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Untuk Pembayaran</td>
            <td>:</td>
            <td>{{ $spj->uraian }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Penerima / Toko</td>
            <td>:</td>
            <td>{{ $spj->penerima_toko ?? '-' }}</td>
        </tr>
    </table>

    <div class="my-4">
        <span class="box-nominal">
            Rp {{ number_format($spj->nominal, 0, ',', '.') }}
        </span>
    </div>

    <table class="w-100 text-center mt-5">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br><strong>Kepala Sekolah</strong>
                <br><br><br><br>
                <u><strong>{{ $schoolProfile->nama_kepala_sekolah ?? '..............................' }}</strong></u>
            </td>
            <td style="width: 50%;">
                {{ $schoolProfile->kabupaten_kota ?? 'Brebes' }}, {{ $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('d F Y') : '................' }}<br><strong>Bendahara Sekolah</strong>
                <br><br><br><br>
                <u><strong>{{ $schoolProfile->nama_bendahara ?? '..............................' }}</strong></u>
            </td>
        </tr>
    </table>
</div>

</body>
</html>