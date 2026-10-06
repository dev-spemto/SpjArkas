@extends('layouts.app')

@section('title', 'Master Kode Kegiatan')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-list-task me-2"></i>Master Kode Kegiatan</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Kode Kegiatan
                </button>
            </div>
            <div class="card-body">
                <form action="{{ route('activity-codes.index') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari kode, nama kegiatan, atau program..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                        @if($search)
                            <a href="{{ route('activity-codes.index') }}" class="btn btn-outline-danger"><i class="bi bi-x-circle"></i> Reset</a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Kode Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Program</th>
                                <th>Keterangan</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityCodes as $index => $item)
                                <tr>
                                    <td>{{ $activityCodes->firstItem() + $index }}</td>
                                    <td><code>{{ $item->kode_kegiatan }}</code></td>
                                    <td class="fw-semibold">{{ $item->nama_kegiatan }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $item->program ?? '-' }}</span></td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('activity-codes.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('activity-codes.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Kode Kegiatan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kode Kegiatan <span class="text-danger">*</span></label>
                                                        <input type="text" name="kode_kegiatan" class="form-control" value="{{ old('kode_kegiatan', $item->kode_kegiatan) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $item->nama_kegiatan) }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Program</label>
                                                        <input type="text" name="program" class="form-control" value="{{ old('program', $item->program) }}">
                                                    </div>
                                                    <div class="mb-3">
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
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data kode kegiatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $activityCodes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('activity-codes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kode Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_kegiatan" class="form-control" placeholder="Contoh: 03.02.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Pelaksanaan Asesmen Nasional" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program</label>
                        <input type="text" name="program" class="form-control" placeholder="Contoh: Pengembangan Standar Proses">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection