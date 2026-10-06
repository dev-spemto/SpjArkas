@extends('layouts.app')

@section('title', 'Daftar Transaksi SPJ')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-receipt me-2"></i>Daftar Transaksi SPJ (Buku Kas Pembantu)
                    @if($jenisBos)
                        <span class="badge bg-primary fs-6 ms-2">BOS {{ $jenisBos }}</span>
                    @else
                        <span class="badge bg-secondary fs-6 ms-2">Semua Jenis BOS</span>
                    @endif
                </h5>
                <div class="d-flex gap-2">
                    @if($spjs->count() > 0)
                        <!-- Tombol Export Excel -->
                        <button type="button" onclick="exportKeExcel()" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                        </button>

                        <!-- Dropdown Cetak Bundle (Berlaku Untuk Semua Jenis BOS & Tipe Kwitansi) -->
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-printer me-1"></i>Cetak Bundle Halaman Ini
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => implode(',', $spjs->pluck('id')->toArray()), 'type' => 'a2']) }}" target="_blank">
                                        <i class="bi bi-file-earmark-text me-2"></i>Bundle Kwitansi A2
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => implode(',', $spjs->pluck('id')->toArray()), 'type' => 'a2_bpu']) }}" target="_blank">
                                        <i class="bi bi-file-earmark-text me-2"></i>Bundle Kwitansi A2 (BPU Sama)
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => implode(',', $spjs->pluck('id')->toArray()), 'type' => 'umum']) }}" target="_blank">
                                        <i class="bi bi-receipt me-2"></i>Bundle Kwitansi Umum Sekolah
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => implode(',', $spjs->pluck('id')->toArray()), 'type' => 'umum_bpu']) }}" target="_blank">
                                        <i class="bi bi-receipt me-2"></i>Bundle Kwitansi Umum (BPU Sama)
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    <a href="{{ route('spjs.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Transaksi SPJ
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <form action="{{ route('spjs.index') }}" method="GET" class="row g-2 mb-4">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari No BKP, Uraian, Toko..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <select name="jenis_bos" class="form-select form-select-sm">
                            <option value="">-- Semua Jenis BOS --</option>
                            <option value="Reguler" {{ ($jenisBos ?? '') == 'Reguler' ? 'selected' : '' }}>BOS Reguler</option>
                            <option value="Kinerja" {{ ($jenisBos ?? '') == 'Kinerja' ? 'selected' : '' }}>BOS Kinerja</option>
                            <option value="Daerah" {{ ($jenisBos ?? '') == 'Daerah' ? 'selected' : '' }}>BOS Daerah</option>
                            <option value="Afirmasi" {{ ($jenisBos ?? '') == 'Afirmasi' ? 'selected' : '' }}>BOS Afirmasi</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate ?? '' }}" title="Tanggal Mulai">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate ?? '' }}" title="Tanggal Akhir">
                    </div>
                    <div class="col-md-3 d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary w-100" type="submit"><i class="bi bi-search me-1"></i>Filter</button>
                        @if($search ||$jenisBos || $startDate ||$endDate)
                            <a href="{{ route('spjs.index') }}" class="btn btn-sm btn-outline-danger" title="Reset Filter"><i class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>

                <!-- Table BKP Format -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-nowrap small" id="tabelSpj">
                        <thead class="table-light text-center fw-bold align-middle" style="background-color: #fce4d6;">
                            <tr>
                                <th style="width: 40px;">NO</th>
                                <th>TANGGAL</th>
                                <th>NO BUKTI</th>
                                <th>KODE KEGIATAN</th>
                                <th>KODE REKENING</th>
                                <th>NAMA REKENING</th>
                                <th>URAIAN</th>
                                <th>PENERIMAAN</th>
                                <th>PENGELUARAN</th>
                                <th>SALDO</th>
                                <th>PENERIMA UANG</th>
                                <th>LAMPIRAN</th>
                                <th>AKSI & CETAK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $runningSaldo =$saldoAwal; @endphp
                            @forelse($spjs as $index =>$item)
                                @php
                                    $isPenerimaan = ($item->jenis_transaksi == 'Penerimaan');$penerimaan = $isPenerimaan ? $item->nominal : 0;
                                    $pengeluaran = !$isPenerimaan ? $item->nominal : 0;
                                    $runningSaldo += ($penerimaan -$pengeluaran);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $spjs->firstItem() +$index }}</td>
                                    <td class="text-center">{{ $item->tanggal_transaksi ? $item->tanggal_transaksi->format('d/m/Y') : '-' }}</td>
                                    <td class="text-center fw-bold"><code>{{ $item->no_bkp ?? '-' }}</code></td>
                                    <td class="text-center">{{ $item->kode_kegiatan ?? '-' }}</td>
                                    <td class="text-center"><code>{{ $item->kode_rekening ?? '-' }}</code></td>
                                    <td>{{ $item->nama_rekening ?? '-' }}</td>
                                    <td>{{ $item->uraian }}</td>
                                    <td class="text-end fw-bold text-success">
                                        {{ $isPenerimaan ? 'Rp ' . number_format($penerimaan, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ !$isPenerimaan ? 'Rp ' . number_format($pengeluaran, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($runningSaldo, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $item->penerima_toko ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($item->file_pdf_path)
                                            <a href="{{ asset('storage/' . $item->file_pdf_path) }}" target="_blank" class="btn btn-xs btn-outline-danger py-0 px-2">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle me-1 py-0 px-2" type="button" data-bs-toggle="dropdown" title="Cetak Kuitansi Bundle">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                <li><a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => $item->id, 'type' => 'a2']) }}" target="_blank"><i class="bi bi-file-earmark-text me-2"></i>Kwitansi A2</a></li>
                                                <li><a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => $item->id, 'type' => 'a2_bpu']) }}" target="_blank"><i class="bi bi-file-earmark-text me-2"></i>Kwitansi A2 (BPU Sama)</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => $item->id, 'type' => 'umum']) }}" target="_blank"><i class="bi bi-receipt me-2"></i>Kwitansi Umum Sekolah</a></li>
                                                <li><a class="dropdown-item small" href="{{ route('spjs.print-bundle', ['ids' => $item->id, 'type' => 'umum_bpu']) }}" target="_blank"><i class="bi bi-receipt me-2"></i>Kwitansi Umum (BPU Sama)</a></li>
                                            </ul>
                                        </div>
                                        <a href="{{ route('spjs.edit', $item->id) }}" class="btn btn-sm btn-outline-warning me-1 py-0 px-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('spjs.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi SPJ ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4 text-muted">Belum ada transaksi SPJ yang dicatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($spjs->count() > 0)
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="7" class="text-end">Subtotal Halaman Ini:</td>
                                    <td class="text-end text-success">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                                    <td class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                    <td class="text-end text-primary">Rp {{ number_format($runningSaldo, 0, ',', '.') }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $spjs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function exportKeExcel() {
        const originalTable = document.getElementById("tabelSpj");
        if (!originalTable) {
            alert("Tabel transaksi tidak ditemukan!");
            return;
        }

        const clonedTable = originalTable.cloneNode(true);
        const rows = clonedTable.querySelectorAll("tr");

        rows.forEach(row => {
            if (row.cells.length >= 13) {
                row.deleteCell(12); // Hapus kolom Aksi & Cetak
                row.deleteCell(11); // Hapus kolom Lampiran
            }
        });

        const wb = XLSX.utils.table_to_book(clonedTable, { sheet: "BKU SPJ" });
        const jenisBos = "{{ request('jenis_bos') ? request('jenis_bos') : 'Semua_Jenis_BOS' }}";
        const fileName = `Rekap_SPJ_${jenisBos}_{{ date('Ymd_His') }}.xlsx`;

        XLSX.writeFile(wb, fileName);
    }
</script>
@endpush