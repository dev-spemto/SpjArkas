@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Banner Utama -->
    <div class="card border-0 shadow-sm text-white mb-4 rounded-3" style="background: linear-gradient(135deg, #0e4d2a 0%, #1d7a46 100%);">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                @if(isset($profile) && $profile->logo)
                    <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo Sekolah" class="bg-white p-1 rounded-3 shadow-sm" style="height: 65px; width: 65px; object-fit: contain;">
                @else
                    <div class="bg-white text-success rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="height: 65px; width: 65px;">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                @endif
                <div>
                    <span class="badge bg-white text-success fw-bold px-3 py-2 mb-2">Identitas Sekolah (Terkunci)</span>
                    <h3 class="fw-bold mb-1">{{ $profile->nama_sekolah ?? 'SMP Muhammadiyah Tonjong' }}</h3>
                    <p class="mb-0 text-white-50">Data identitas default untuk pencetakan dokumen, BKU, & kwitansi BOSP.</p>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-light fw-bold text-success shadow-sm px-3 py-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profil Sekolah
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Grid Kartu Profil Terkunci (Read-Only View) -->
    <div class="row g-3">
        <!-- Informasi Umum & Alamat -->
        <div class="col-md-6">
            <div class="card border border-secondary-subtle shadow-sm h-100 rounded-3">
                <div class="card-header bg-body-tertiary fw-bold py-3 border-bottom border-secondary-subtle">
                    <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Umum & Alamat
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless table-sm mb-0 align-middle">
                        <tr>
                            <td class="text-muted" style="width: 170px;">Nama Sekolah</td>
                            <td class="fw-bold text-body">: {{ $profile->nama_sekolah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NPSN</td>
                            <td class="fw-bold text-body">: {{ $profile->npsn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat Sekolah</td>
                            <td class="text-body">: {{ $profile->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kecamatan</td>
                            <td class="text-body">: {{ $profile->kecamatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kabupaten / Kota</td>
                            <td class="text-body">: {{ $profile->kabupaten_kota ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Provinsi</td>
                            <td class="text-body">: {{ $profile->provinsi ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Penandatangan & Redaksi -->
        <div class="col-md-6">
            <div class="card border border-secondary-subtle shadow-sm h-100 rounded-3">
                <div class="card-header bg-body-tertiary fw-bold py-3 border-bottom border-secondary-subtle">
                    <i class="bi bi-person-badge me-2 text-success"></i>Penandatangan Dokumen & Kwitansi
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless table-sm mb-0 align-middle">
                        <tr>
                            <td class="text-muted" style="width: 170px;">Kepala Sekolah</td>
                            <td class="fw-bold text-body">: {{ $profile->nama_kepala_sekolah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIP Kepala Sekolah</td>
                            <td class="text-body">: {{ $profile->nip_kepala_sekolah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bendahara Sekolah</td>
                            <td class="fw-bold text-body">: {{ $profile->nama_bendahara ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIP Bendahara</td>
                            <td class="text-body">: {{ $profile->nip_bendahara ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Komite Sekolah</td>
                            <td class="fw-bold text-body">: {{ $profile->nama_komite ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIP/NIK Komite</td>
                            <td class="text-body">: {{ $profile->nip_komite ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Redaksi Diterima Dari</td>
                            <td class="small text-body">: {{ $profile->redaksi_diterima ?? 'Bendahara BOSP SMP Muhammadiyah Tonjong' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pop-up Edit Profil Sekolah -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('school-profile.storeOrUpdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editProfileModalLabel"><i class="bi bi-pencil-square me-2 text-success"></i>Edit Profil Sekolah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sekolah" class="form-control" value="{{ old('nama_sekolah', $profile->nama_sekolah ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">NPSN</label>
                            <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $profile->npsn ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Sekolah</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $profile->kecamatan ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota', $profile->kabupaten_kota ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $profile->provinsi ?? '') }}">
                        </div>

                        <hr class="my-2">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kepala Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kepala_sekolah" class="form-control" value="{{ old('nama_kepala_sekolah', $profile->nama_kepala_sekolah ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP Kepala Sekolah</label>
                            <input type="text" name="nip_kepala_sekolah" class="form-control" value="{{ old('nip_kepala_sekolah', $profile->nip_kepala_sekolah ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Bendahara Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_bendahara" class="form-control" value="{{ old('nama_bendahara', $profile->nama_bendahara ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP Bendahara Sekolah</label>
                            <input type="text" name="nip_bendahara" class="form-control" value="{{ old('nip_bendahara', $profile->nip_bendahara ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Komite Sekolah</label>
                            <input type="text" name="nama_komite" class="form-control" value="{{ old('nama_komite', $profile->nama_komite ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP Komite Sekolah (jika ada)</label>
                            <input type="text" name="nip_komite" class="form-control" value="{{ old('nip_komite', $profile->nip_komite ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Diterima Dari (Untuk Teks Kwitansi)</label>
                            <input type="text" name="redaksi_diterima" class="form-control" value="{{ old('redaksi_diterima', $profile->redaksi_diterima ?? 'Bendahara BOSP SMP Muhammadiyah Tonjong') }}" placeholder="Contoh: Bendahara BOSP SMP Muhammadiyah Tonjong">
                            <div class="form-text">Isi entitas/subjek pembayar tanpa perlu menyertakan awalan 'Sudah Diterima Dari:'.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Logo Sekolah</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">Format: .png, .jpg, .jpeg, .svg (Maks. 2 MB).</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection