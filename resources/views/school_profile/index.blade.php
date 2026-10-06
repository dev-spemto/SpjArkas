@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-building me-2"></i>Pengaturan Profil Sekolah</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('school-profile.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label font-weight-bold">Nama Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sekolah" class="form-control" value="{{ old('nama_sekolah', $profile->nama_sekolah ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NPSN</label>
                            <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $profile->npsn ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat Sekolah</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $profile->kecamatan ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kabupaten / Kota</label>
                            <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota', $profile->kabupaten_kota ?? 'Brebes') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $profile->provinsi ?? 'Jawa Tengah') }}">
                        </div>

                        <hr class="my-4">

                        <div class="col-md-6">
                            <label class="form-label">Nama Kepala Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kepala_sekolah" class="form-control" value="{{ old('nama_kepala_sekolah', $profile->nama_kepala_sekolah ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP Kepala Sekolah</label>
                            <input type="text" name="nip_kepala_sekolah" class="form-control" value="{{ old('nip_kepala_sekolah', $profile->nip_kepala_sekolah ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Bendahara Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_bendahara" class="form-control" value="{{ old('nama_bendahara', $profile->nama_bendahara ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP Bendahara Sekolah</label>
                            <input type="text" name="nip_bendahara" class="form-control" value="{{ old('nip_bendahara', $profile->nip_bendahara ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Komite Sekolah</label>
                            <input type="text" name="nama_komite" class="form-control" value="{{ old('nama_komite', $profile->nama_komite ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP Komite Sekolah (jika ada)</label>
                            <input type="text" name="nip_komite" class="form-control" value="{{ old('nip_komite', $profile->nip_komite ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Redaksi Telah Diterima</label>
                            <input type="text" name="redaksi_diterima" class="form-control" value="{{ old('redaksi_diterima', $profile->redaksi_diterima ?? 'Bendahara Bantuan Operasional Sekolah') }}" placeholder="Contoh: Bendahara Bantuan Operasional Sekolah">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Logo Sekolah</label>
                            @if(isset($profile) && $profile->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo Sekolah" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Profil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection