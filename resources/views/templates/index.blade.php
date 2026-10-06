@extends('layouts.app')

@section('title', 'Download Template Document BOSP')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Page -->
    <div class="card border-0 shadow-sm text-white mb-4 rounded-3" style="background: linear-gradient(135deg, #0e4d2a 0%, #1d7a46 100%);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <span class="badge bg-white text-success fw-bold px-3 py-2 mb-2">Pusat File Template</span>
                    <h3 class="fw-bold mb-1"><i class="bi bi-file-earmark-arrow-down me-2"></i>Download & Kelola Template Document</h3>
                    <p class="mb-0 text-white-50">Tempat menyimpan dan mengunduh format berkas rutin (SK, Berita Acara, Format Honor, dll).</p>
                </div>
                <div>
                    <button type="button" class="btn btn-light fw-bold text-success shadow-sm rounded-3 px-3 py-2" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                        <i class="bi bi-cloud-upload-fill me-2"></i>Unggah Template Baru
                    </button>
                </div>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Daftar Card Template (Dinamis dari Database) -->
    <div class="row g-3">
        @forelse($templates as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card border border-secondary-subtle shadow-sm h-100 rounded-3">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-7 fw-semibold">
                                    {{ $item->category ?? 'Umum' }}
                                </span>
                                <span class="badge bg-secondary text-white font-monospace">{{ $item->file_extension }}</span>
                            </div>
                            <h5 class="fw-bold text-body mb-2">{{ $item->title }}</h5>
                            <p class="text-muted small mb-3">{{ $item->description ?? 'Tidak ada keterangan tambahan.' }}</p>
                        </div>

                        <div class="pt-3 border-top border-secondary-subtle d-flex justify-content-between align-items-center">
                            <small class="text-muted text-truncate me-2" style="max-width: 160px;" title="{{ $item->original_name }}">
                                <i class="bi bi-file-earmark-code me-1"></i>{{ $item->original_name }}
                            </small>
                            <div class="d-flex gap-1">
                                <a href="{{ route('templates.download', $item->id) }}" class="btn btn-sm btn-outline-success fw-bold px-3">
                                    <i class="bi bi-download me-1"></i>Unduh
                                </a>
                                <form action="{{ route('templates.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Hapus Template">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border border-dashed text-center py-5 shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-folder2-open display-3 text-muted mb-3 d-block"></i>
                        <h5 class="fw-bold text-muted">Belum Ada Template Berkas</h5>
                        <p class="text-muted small mb-3">Silakan klik tombol di bawah untuk mulai mengunggah template pertama Anda.</p>
                        <button type="button" class="btn btn-sm btn-success fw-bold px-3" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                            <i class="bi bi-cloud-upload me-1"></i>Unggah Template Baru
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Upload Template -->
<div class="modal fade" id="uploadTemplateModal" tabindex="-1" aria-labelledby="uploadTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="uploadTemplateModalLabel"><i class="bi bi-cloud-upload me-2 text-success"></i>Unggah Template Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Template <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: SK Tim Manajemen BOSP 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Berkas</label>
                        <input type="text" name="category" class="form-control" placeholder="Contoh: SK, Berita Acara, Honorarium, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan / Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan singkat mengenai berkas ini..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Berkas <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required>
                        <div class="form-text">Format yang didukung: .docx, .doc, .xlsx, .xls, .pdf, .zip, .rar (Maks. 20 MB).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-upload me-1"></i>Unggah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection