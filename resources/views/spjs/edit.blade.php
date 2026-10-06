@extends('layouts.app')

@section('title', 'Edit Transaksi SPJ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div>
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Transaksi SPJ</h5>
                    <small class="text-muted">Perbarui data transaksi Buku Kas Pembantu (BKP)</small>
                </div>
                <a href="{{ route('spjs.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('spjs.update', $spj->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. BKP / Bukti</label>
                            <input type="text" name="no_bkp" class="form-control" value="{{ old('no_bkp', $spj->no_bkp) }}" placeholder="Contoh: BKP-001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_transaksi" class="form-control" value="{{ old('tanggal_transaksi', $spj->tanggal_transaksi ? $spj->tanggal_transaksi->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jenis BOSP <span class="text-danger">*</span></label>
                            <select name="jenis_bos" class="form-select" required>
                                <option value="Reguler" {{ old('jenis_bos', $spj->jenis_bos) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                <option value="Kinerja" {{ old('jenis_bos', $spj->jenis_bos) == 'Kinerja' ? 'selected' : '' }}>Kinerja</option>
                                <option value="Daerah" {{ old('jenis_bos', $spj->jenis_bos) == 'Daerah' ? 'selected' : '' }}>Daerah</option>
                                <option value="Afirmasi" {{ old('jenis_bos', $spj->jenis_bos) == 'Afirmasi' ? 'selected' : '' }}>Afirmasi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jenis Transaksi <span class="text-danger">*</span></label>
                            <select name="jenis_transaksi" class="form-select" required>
                                <option value="Pengeluaran" {{ old('jenis_transaksi', $spj->jenis_transaksi ?? 'Pengeluaran') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran (Kredit)</option>
                                <option value="Penerimaan" {{ old('jenis_transaksi', $spj->jenis_transaksi ?? '') == 'Penerimaan' ? 'selected' : '' }}>Penerimaan (Debet)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Kegiatan ARKAS</label>
                            <select id="kode_kegiatan_select" class="form-select" onchange="updateKegiatanInfo()">
                                <option value="">-- Pilih Kode Kegiatan --</option>
                                @foreach($activityCodes as $act)
                                    <option value="{{ $act->kode_kegiatan }}" data-nama="{{ $act->nama_kegiatan }}" {{ old('kode_kegiatan', $spj->kode_kegiatan) == $act->kode_kegiatan ? 'selected' : '' }}>
                                        {{ $act->kode_kegiatan }} - {{ $act->nama_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="kode_kegiatan" id="kode_kegiatan" value="{{ old('kode_kegiatan', $spj->kode_kegiatan) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control bg-light" value="{{ old('nama_kegiatan', $spj->nama_kegiatan) }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Rekening ARKAS</label>
                            <select id="kode_rekening_select" class="form-select" onchange="updateRekeningInfo()">
                                <option value="">-- Pilih Kode Rekening --</option>
                                @foreach($accountCodes as $acc)
                                    <option value="{{ $acc->kode_rekening }}" data-nama="{{ $acc->nama_rekening }}" {{ old('kode_rekening', $spj->kode_rekening) == $acc->kode_rekening ? 'selected' : '' }}>
                                        {{ $acc->kode_rekening }} - {{ $acc->nama_rekening }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="kode_rekening" id="kode_rekening" value="{{ old('kode_rekening', $spj->kode_rekening) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Nama Rekening</label>
                            <input type="text" name="nama_rekening" id="nama_rekening" class="form-control bg-light" value="{{ old('nama_rekening', $spj->nama_rekening) }}" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Uraian Transaksi <span class="text-danger">*</span></label>
                            <textarea name="uraian" class="form-control" rows="3" required>{{ old('uraian', $spj->uraian) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" step="0.01" name="nominal" class="form-control" value="{{ old('nominal', $spj->nominal) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penerima Uang / Toko / Penyedia</label>
                            <input type="text" name="penerima_toko" class="form-control" value="{{ old('penerima_toko', $spj->penerima_toko) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload Berkas PDF SPJ Baru (Ganti Berkas)</label>
                            <input type="file" name="file_pdf" class="form-control" accept="application/pdf">
                            @if($spj->file_pdf_path)
                                <div class="mt-2">
                                    <small class="text-muted">File saat ini: 
                                        <a href="{{ asset('storage/' . $spj->file_pdf_path) }}" target="_blank" class="text-decoration-none fw-semibold">
                                            <i class="bi bi-file-earmark-pdf me-1 text-danger"></i>Lihat PDF Terlampir
                                        </a>
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <a href="{{ route('spjs.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateKegiatanInfo() {
        var select = document.getElementById('kode_kegiatan_select');
        var selectedOption = select.options[select.selectedIndex];
        document.getElementById('kode_kegiatan').value = select.value;
        document.getElementById('nama_kegiatan').value = selectedOption.getAttribute('data-nama') || '';
    }

    function updateRekeningInfo() {
        var select = document.getElementById('kode_rekening_select');
        var selectedOption = select.options[select.selectedIndex];
        document.getElementById('kode_rekening').value = select.value;
        document.getElementById('nama_rekening').value = selectedOption.getAttribute('data-nama') || '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateKegiatanInfo();
        updateRekeningInfo();
    });
</script>
@endpush