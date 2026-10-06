@extends('layouts.app')

@section('title', 'Arsip Dokumen BOSP')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-folder-symlink me-2"></i>Arsip Dokumen BOSP (RKAS & SPJ)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Dokumen
                </button>
            </div>
            <div class="card-body">
                <form action="{{ route('bosp-documents.index') }}" method="GET" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama dokumen / keterangan..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="jenis_bos" class="form-select">
                            <option value="">-- Semua Jenis BOSP --</option>
                            <option value="Reguler" {{ ($jenisBos ?? '') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Kinerja" {{ ($jenisBos ?? '') == 'Kinerja' ? 'selected' : '' }}>Kinerja</option>
                            <option value="Daerah" {{ ($jenisBos ?? '') == 'Daerah' ? 'selected' : '' }}>Daerah</option>
                            <option value="Afirmasi" {{ ($jenisBos ?? '') == 'Afirmasi' ? 'selected' : '' }}>Afirmasi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="tahun" class="form-control" placeholder="Tahun (Contoh: 2026)" value="{{ $tahun ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button class="btn btn-outline-secondary w-100" type="submit"><i class="bi bi-search"></i> Filter</button>
                        @if($search || $jenisBos || $tahun)
                            <a href="{{ route('bosp-documents.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Dokumen</th>
                                <th>Jenis & Tahap</th>
                                <th>Tahun</th>
                                <th>File RKAS</th>
                                <th>File SPJ</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $index => $item)
                                <tr>
                                    <td>{{ $documents->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->nama_dokumen }}</div>
                                        <small class="text-muted">{{ $item->keterangan ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary me-1">{{ $item->jenis_bos }}</span>
                                        <small class="text-secondary">{{ $item->tahap ?? '-' }}</small>
                                    </td>
                                    <td class="fw-bold">{{ $item->tahun }}</td>
                                    <td>
                                        @if($item->file_rkas_path)
                                            <a href="{{ asset('storage/' . $item->file_rkas_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>RKAS
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->file_spj_path)
                                            <a href="{{ asset('storage/' . $item->file_spj_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>SPJ
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('bosp-documents.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('bosp-documents.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Dokumen BOSP</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_dokumen" class="form-control" value="{{ old('nama_dokumen', $item->nama_dokumen) }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Jenis BOSP <span class="text-danger">*</span></label>
                                                        <select name="jenis_bos" class="form-select" required>
                                                            <option value="Reguler" {{ $item->jenis_bos == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                                            <option value="Kinerja" {{ $item->jenis_bos == 'Kinerja' ? 'selected' : '' }}>Kinerja</option>
                                                            <option value="Daerah" {{ $item->jenis_bos == 'Daerah' ? 'selected' : '' }}>Daerah</option>
                                                            <option value="Afirmasi" {{ $item->jenis_bos == 'Afirmasi' ? 'selected' : '' }}>Afirmasi</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                                                        <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $item->tahun) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Tahap / Gelombang</label>
                                                        <input type="text" name="tahap" class="form-control" value="{{ old('tahap', $item->tahap) }}" placeholder="Contoh: Tahap 1">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">File RKAS (PDF)</label>
                                                        <input type="file" name="file_rkas" class="form-control" accept="application/pdf">
                                                        @if($item->file_rkas_path)
                                                            <small class="text-muted">File saat ini: Tersedia</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">File SPJ (PDF)</label>
                                                        <input type="file" name="file_spj" class="form-control" accept="application/pdf">
                                                        @if($item->file_spj_path)
                                                            <small class="text-muted">File saat ini: Tersedia</small>
                                                        @endif
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Keterangan</label>
                                                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $item->keterangan) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada arsip dokumen BOSP.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('bosp-documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Arsip Dokumen BOSP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_dokumen" class="form-control" placeholder="Contoh: RKAS & SPJ Tahap 1 BOSP 2026" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis BOSP <span class="text-danger">*</span></label>
                        <select name="jenis_bos" class="form-select" required>
                            <option value="Reguler" selected>Reguler</option>
                            <option value="Kinerja">Kinerja</option>
                            <option value="Daerah">Daerah</option>
                            <option value="Afirmasi">Afirmasi</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahap / Gelombang</label>
                        <input type="text" name="tahap" class="form-control" placeholder="Contoh: Tahap 1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload File RKAS (PDF)</label>
                        <input type="file" name="file_rkas" class="form-control" accept="application/pdf">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload File SPJ (PDF)</label>
                        <input type="file" name="file_spj" class="form-control" accept="application/pdf">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan atau uraian ringkas..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection