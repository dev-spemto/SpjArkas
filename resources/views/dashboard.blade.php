@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Welcome -->
    <div class="card border-0 shadow-sm text-white mb-4 rounded-3" style="background: linear-gradient(135deg, #0e4d2a 0%, #1d7a46 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-white text-success fw-bold px-3 py-2 mb-2">Tahun Anggaran {{ $currentYear }}</span>
                    <h3 class="fw-bold mb-1">Selamat Datang di Sistem SPJ & BOSP</h3>
                    <p class="mb-0 text-white-50">Monitoring Realisasi Anggaran, Progress SPJ Bulanan, dan Pengarsipan Berkas Sekolah.</p>
                </div>
                <!-- Widget Penanda Waktu -->
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="bg-black bg-opacity-30 text-white rounded-3 p-3 d-inline-block text-start border border-white border-opacity-25 shadow-sm" style="min-width: 230px; backdrop-filter: blur(4px);">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-clock-fill text-warning fs-5"></i>
                            <span class="fw-bold fs-5 text-white" id="realtime-clock" style="letter-spacing: 0.5px;">00:00:00 WIB</span>
                        </div>
                        <small class="d-block text-white fw-bold" id="realtime-date">
                            <i class="bi bi-calendar-event me-1 text-warning"></i>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 1: Progress Pengerjaan & Tahun Selesai -->
    <div class="row g-3 mb-4">
        <!-- Progress Pengerjaan Bulanan -->
        <div class="col-md-7">
            <div class="card border border-secondary-subtle shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-speedometer2 text-primary me-2"></i>Progress Input BOSP {{ $currentYear }}</h6>
                        <span class="badge bg-primary-subtle text-primary fw-bold fs-6">{{ $latestMonthNumber }} / 12 Bulan</span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Status Terakhir Input: <strong>Bulan {{ $latestMonthName }}</strong></span>
                            <span class="fw-bold text-primary">{{ $progressPercentage }}%</span>
                        </div>
                        <div class="progress" style="height: 14px; border-radius: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                 role="progressbar" 
                                 style="width: {{ $progressPercentage }}%;">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-body-tertiary rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-primary fs-5 me-2"></i>
                            <small class="text-muted">Progres dihitung dari bulan pada transaksi pengeluaran SPJ terakhir yang dimasukkan.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tahun BOSP Selesai -->
        <div class="col-md-5">
            <div class="card border border-secondary-subtle shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Tahun BOSP Selesai (Arsip Laporan)</h6>
                    <p class="text-muted small mb-3">Terverifikasi lengkap berdasarkan dokumen BOSP yang sudah di-upload:</p>

                    @if($completedYears->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($completedYears as $tahun)
                                <div class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 fs-6 rounded-3">
                                    <i class="bi bi-patch-check-fill me-1"></i> BOSP {{ $tahun }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning border-0 small mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> Belum ada dokumen BOSP tahunan yang di-upload di menu <strong>Pembukuan BOSP</strong>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Cards Ringkasan Saldo Per Sumber Dana -->
    <h6 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2"></i>Ringkasan Kas Per Sumber Dana ({{ $currentYear }})</h6>
    <div class="row g-3">
        <!-- BOS Reguler -->
        <div class="col-md-3">
            <div class="card border border-secondary-subtle border-start border-4 border-primary shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-muted small">BOS REGULER</span>
                        <span class="badge bg-primary-subtle text-primary">{{ $ringkasanBos['Reguler']['total_transaksi'] }} Transaksi</span>
                    </div>
                    <h5 class="fw-bold text-primary mb-1">Rp {{ number_format($ringkasanBos['Reguler']['saldo'], 0, ',', '.') }}</h5>
                    <small class="text-muted d-block fs-7">Keluar: Rp {{ number_format($ringkasanBos['Reguler']['pengeluaran'], 0, ',', '.') }}</small>
                    <a href="{{ route('spjs.index', ['jenis_bos' => 'Reguler']) }}" class="btn btn-sm btn-outline-primary w-100 mt-2">Buka BKU Reguler</a>
                </div>
            </div>
        </div>

        <!-- BOS Afirmasi -->
        <div class="col-md-3">
            <div class="card border border-secondary-subtle border-start border-4 border-warning shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-muted small">BOS AFIRMASI</span>
                        <span class="badge bg-warning-subtle text-warning-emphasis">{{ $ringkasanBos['Afirmasi']['total_transaksi'] }} Transaksi</span>
                    </div>
                    <h5 class="fw-bold text-warning-emphasis mb-1">Rp {{ number_format($ringkasanBos['Afirmasi']['saldo'], 0, ',', '.') }}</h5>
                    <small class="text-muted d-block fs-7">Keluar: Rp {{ number_format($ringkasanBos['Afirmasi']['pengeluaran'], 0, ',', '.') }}</small>
                    <a href="{{ route('spjs.index', ['jenis_bos' => 'Afirmasi']) }}" class="btn btn-sm btn-outline-warning w-100 mt-2">Buka BKU Afirmasi</a>
                </div>
            </div>
        </div>

        <!-- BOS Kinerja -->
        <div class="col-md-3">
            <div class="card border border-secondary-subtle border-start border-4 border-success shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-muted small">BOS KINERJA</span>
                        <span class="badge bg-success-subtle text-success">{{ $ringkasanBos['Kinerja']['total_transaksi'] }} Transaksi</span>
                    </div>
                    <h5 class="fw-bold text-success mb-1">Rp {{ number_format($ringkasanBos['Kinerja']['saldo'], 0, ',', '.') }}</h5>
                    <small class="text-muted d-block fs-7">Keluar: Rp {{ number_format($ringkasanBos['Kinerja']['pengeluaran'], 0, ',', '.') }}</small>
                    <a href="{{ route('spjs.index', ['jenis_bos' => 'Kinerja']) }}" class="btn btn-sm btn-outline-success w-100 mt-2">Buka BKU Kinerja</a>
                </div>
            </div>
        </div>

        <!-- BOS Daerah -->
        <div class="col-md-3">
            <div class="card border border-secondary-subtle border-start border-4 border-info shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-muted small">BOS DAERAH</span>
                        <span class="badge bg-info-subtle text-info-emphasis">{{ $ringkasanBos['Daerah']['total_transaksi'] }} Transaksi</span>
                    </div>
                    <h5 class="fw-bold text-info-emphasis mb-1">Rp {{ number_format($ringkasanBos['Daerah']['saldo'], 0, ',', '.') }}</h5>
                    <small class="text-muted d-block fs-7">Keluar: Rp {{ number_format($ringkasanBos['Daerah']['pengeluaran'], 0, ',', '.') }}</small>
                    <a href="{{ route('spjs.index', ['jenis_bos' => 'Daerah']) }}" class="btn btn-sm btn-outline-info w-100 mt-2">Buka BKU Daerah</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockElement = document.getElementById('realtime-clock');
        if (clockElement) {
            clockElement.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }
    
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush