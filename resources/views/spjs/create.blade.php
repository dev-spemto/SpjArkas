@extends('layouts.app')

@section('title', 'Tambah Transaksi SPJ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div>
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Transaksi SPJ Baru</h5>
                    <small class="text-muted">Isi formulir di bawah ini untuk mencatat transaksi Buku Kas Pembantu (BKP)</small>
                </div>
                <a href="{{ route('spjs.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('spjs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row g-3">
                        <!-- Baris 1: Informasi Dasar -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. BKP / Bukti</label>
                            <input type="text" name="no_bkp" class="form-control" value="{{ old('no_bkp') }}" placeholder="Contoh: BKP-001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_transaksi" class="form-control" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jenis BOSP <span class="text-danger">*</span></label>
                            <select name="jenis_bos" class="form-select" required>
                                <option value="Reguler" {{ old('jenis_bos') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                <option value="Kinerja" {{ old('jenis_bos') == 'Kinerja' ? 'selected' : '' }}>Kinerja</option>
                                <option value="Daerah" {{ old('jenis_bos') == 'Daerah' ? 'selected' : '' }}>Daerah</option>
                                <option value="Afirmasi" {{ old('jenis_bos') == 'Afirmasi' ? 'selected' : '' }}>Afirmasi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Jenis Transaksi <span class="text-danger">*</span></label>
                            <select name="jenis_transaksi" class="form-select" required>
                                <option value="Pengeluaran" {{ old('jenis_transaksi', 'Pengeluaran') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran (Kredit)</option>
                                <option value="Penerimaan" {{ old('jenis_transaksi') == 'Penerimaan' ? 'selected' : '' }}>Penerimaan (Debet)</option>
                            </select>
                        </div>

                        <!-- Baris 2: Kode Kegiatan & Nama Kegiatan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Kegiatan ARKAS</label>
                            <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control" list="list_kegiatan" value="{{ old('kode_kegiatan') }}" placeholder="Ketik manual atau pilih dari daftar..." oninput="autoFillKegiatan(this.value)">
                            <datalist id="list_kegiatan">
                                @foreach($activityCodes as $act)
                                    <option value="{{ $act->kode_kegiatan }}">{{ $act->nama_kegiatan }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan') }}" placeholder="Isi nama kegiatan...">
                        </div>

                        <!-- Baris 3: Kode Rekening & Nama Rekening -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Rekening ARKAS</label>
                            <input type="text" name="kode_rekening" id="kode_rekening" class="form-control" list="list_rekening" value="{{ old('kode_rekening') }}" placeholder="Ketik manual atau pilih dari daftar..." oninput="autoFillRekening(this.value)">
                            <datalist id="list_rekening">
                                @foreach($accountCodes as $acc)
                                    <option value="{{ $acc->kode_rekening }}">{{ $acc->nama_rekening }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Rekening</label>
                            <input type="text" name="nama_rekening" id="nama_rekening" class="form-control" value="{{ old('nama_rekening') }}" placeholder="Isi nama rekening...">
                        </div>

                        <!-- Baris 4: Uraian Transaksi -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Uraian Transaksi <span class="text-danger">*</span></label>
                            <textarea name="uraian" class="form-control" rows="3" placeholder="Contoh: Pembayaran Pembelian Alat Tulis Kantor (ATK) Tahap 1..." required>{{ old('uraian') }}</textarea>
                        </div>

                        <!-- Baris 5: Nominal & Penerima Uang -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nominal (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" step="0.01" name="nominal" class="form-control" value="{{ old('nominal') }}" placeholder="Contoh: 1500000" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penerima Uang / Toko / Penyedia</label>
                            <input type="text" name="penerima_toko" class="form-control" value="{{ old('penerima_toko') }}" placeholder="Contoh: Toko Buku Sumber Ilmu">
                        </div>

                        <!-- Baris 6: Lampiran PDF -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Upload Berkas PDF SPJ / Nota (Opsional)</label>
                            <input type="file" name="file_pdf" class="form-control" accept="application/pdf">
                            <small class="text-muted">Format file yang diperbolehkan: PDF (Maksimal 10MB)</small>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <a href="{{ route('spjs.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan SPJ
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
    const kegiatanMap = {
        @foreach($activityCodes as $act)
            @json($act->kode_kegiatan): @json($act->nama_kegiatan),
        @endforeach
    };

    const rekeningMap = {
        @foreach($accountCodes as $acc)
            @json($acc->kode_rekening): @json($acc->nama_rekening),
        @endforeach
    };

    function autoFillKegiatan(val) {
        if (kegiatanMap[val] !== undefined) {
            document.getElementById('nama_kegiatan').value = kegiatanMap[val];
        }
    }

    function autoFillRekening(val) {
        if (rekeningMap[val] !== undefined) {
            document.getElementById('nama_rekening').value = rekeningMap[val];
        }
    }
</script>
@endpush