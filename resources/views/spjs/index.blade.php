@extends('layouts.app')

@section('title', 'Daftar Transaksi SPJ')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-receipt me-2"></i>Daftar Transaksi SPJ (Buku Kas Pembantu)
                    @if($jenisBos)
                        <span class="badge bg-primary fs-6 ms-2">BOS {{ $jenisBos }}</span>
                    @else
                        <span class="badge bg-secondary fs-6 ms-2">Semua Jenis BOS</span>
                    @endif
                </h5>
                <div class="d-flex gap-2 flex-wrap">
                    @if($spjs->count() > 0)
                        <!-- Tombol Export Excel -->
                        <button type="button" onclick="exportKeExcel()" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                        </button>

                        <!-- Dropdown Cetak Bundle -->
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-printer me-1"></i>Cetak Bundle Transaksi
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

                    <!-- Tombol Download Format Import -->
                    <button type="button" onclick="downloadFormatImport()" class="btn btn-outline-success btn-sm fw-bold">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Format Import
                    </button>

                    <!-- Tombol Buka Modal Import -->
                    <button type="button" class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i>Import Excel
                    </button>

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
                        @if($search || $jenisBos || $startDate || $endDate)
                            <a href="{{ route('spjs.index') }}" class="btn btn-sm btn-outline-danger" title="Reset Filter"><i class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>

                <!-- Table BKP Format -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-nowrap small" id="tabelSpj">
                        <thead class="table-light text-center fw-bold align-middle">
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
                            @php $runningSaldo = $saldoAwal; @endphp
                            @forelse($spjs as $index => $item)
                                @php
                                    $isPenerimaan = ($item->jenis_transaksi == 'Penerimaan');
                                    $penerimaan = $isPenerimaan ? $item->nominal : 0;
                                    $pengeluaran = !$isPenerimaan ? $item->nominal : 0;
                                    $runningSaldo += ($penerimaan - $pengeluaran);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
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
                                    <td colspan="7" class="text-end">Total Pagu Diterima & Pembelanjaan Murni:</td>
                                    <td class="text-end text-success">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</td>
                                    <td class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                    <td class="text-end text-primary">Rp {{ number_format($runningSaldo, 0, ',', '.') }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted small">Menampilkan seluruh {{ $spjs->count() }} data transaksi BKU.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import Excel BKU -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="importExcelModalLabel"><i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>Import Transaksi BKU dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Berkas Excel (.xlsx / .xls) <span class="text-danger">*</span></label>
                    <input type="file" id="excelFileInput" class="form-control" accept=".xlsx, .xls">
                    <div class="form-text mt-2">
                        Gunakan urutan kolom Excel berikut: <code>Tanggal (YYYY-MM-DD), No Bukti, Kode Kegiatan, Kode Rekening, Uraian Transaksi, Penerimaan, Pengeluaran, Toko/Penerima</code>
                    </div>
                </div>

                <!-- Area Preview Tabel Hasil Parse -->
                <div id="previewContainer" class="d-none mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-success"><i class="bi bi-eye me-1"></i>Preview Data Terbaca (<span id="totalRowCount">0</span> baris)</h6>
                    </div>
                    <div class="table-responsive style-scrollbar" style="max-height: 350px;">
                        <table class="table table-sm table-bordered align-middle small mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>No Bukti</th>
                                    <th>Kode Kegiatan</th>
                                    <th>Kode Rek</th>
                                    <th>Uraian Transaksi</th>
                                    <th>Penerimaan</th>
                                    <th>Pengeluaran</th>
                                    <th>Toko / Penerima</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody">
                                <!-- Data hasil parse dari Excel dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSubmitImport" class="btn btn-success fw-bold" disabled onclick="submitImportData()">
                    <i class="bi bi-cloud-upload me-1"></i>Simpan Semua Transaksi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    let parsedTransactions = [];
    const currentJenisBos = "{{ request('jenis_bos', 'Reguler') }}";

    // 1. Download Template Format Excel (Pas 8 Kolom)
    function downloadFormatImport() {
        const templateData = [
            ["Tanggal", "No Bukti", "Kode Kegiatan", "Kode Rekening", "Uraian Transaksi", "Penerimaan", "Pengeluaran", "Toko / Penerima"],
            ["2026-10-01", "KWT/01/2026", "03.02.01", "5.1.02.01.0001", "Pembelian ATK Kegiatan Lomba", 0, 350000, "Toko ATK Jaya"],
            ["2026-10-02", "KWT/02/2026", "03.02.02", "5.1.02.01.0002", "Penerimaan Dana BOSP Tahap 2", 15000000, 0, "Dinas Pendidikan"]
        ];

        const ws = XLSX.utils.aoa_to_sheet(templateData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Format_Import_BKU");
        XLSX.writeFile(wb, `Format_Import_BKU_${currentJenisBos}.xlsx`);
    }

    // 2. Event Listener Parse File Excel saat Diunggah
    const excelInput = document.getElementById('excelFileInput');
    if (excelInput) {
        excelInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: 'array', cellDates: true });
                
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];
                const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                
                parsedTransactions = [];
                const tbody = document.getElementById('previewTableBody');
                tbody.innerHTML = '';

                for (let i = 1; i < jsonData.length; i++) {
                    const row = jsonData[i];
                    if (!row || row.length === 0 || !row[0]) continue;

                    let rawDate = row[0];
                    let formattedDate = '';
                    if (rawDate instanceof Date) {
                        formattedDate = rawDate.toISOString().split('T')[0];
                    } else {
                        formattedDate = String(rawDate).trim();
                    }

                    // Pembacaan Indeks Kolom Pas 8 Kolom (A - H)
                    const item = {
                        tanggal: formattedDate,                            // Kolom A (0)
                        no_bukti: row[1] ? String(row[1]).trim() : '',      // Kolom B (1)
                        activity_code: row[2] ? String(row[2]).trim() : '', // Kolom C (2)
                        account_code: row[3] ? String(row[3]).trim() : '',  // Kolom D (3)
                        uraian: row[4] ? String(row[4]).trim() : '',        // Kolom E (4)
                        penerimaan: row[5] ? parseFloat(row[5]) || 0 : 0,   // Kolom F (5)
                        pengeluaran: row[6] ? parseFloat(row[6]) || 0 : 0,  // Kolom G (6)
                        penerima_toko: row[7] ? String(row[7]).trim() : ''  // Kolom H (7)
                    };

                    parsedTransactions.push(item);

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${parsedTransactions.length}</td>
                        <td>${item.tanggal}</td>
                        <td><code>${item.no_bukti || '-'}</code></td>
                        <td><code>${item.activity_code || '-'}</code></td>
                        <td><code>${item.account_code || '-'}</code></td>
                        <td>${item.uraian}</td>
                        <td class="text-end text-success fw-bold">Rp ${item.penerimaan.toLocaleString('id-ID')}</td>
                        <td class="text-end text-danger fw-bold">Rp ${item.pengeluaran.toLocaleString('id-ID')}</td>
                        <td>${item.penerima_toko || '-'}</td>
                    `;
                    tbody.appendChild(tr);
                }

                if (parsedTransactions.length > 0) {
                    document.getElementById('totalRowCount').textContent = parsedTransactions.length;
                    document.getElementById('previewContainer').classList.remove('d-none');
                    document.getElementById('btnSubmitImport').disabled = false;
                } else {
                    alert('File Excel kosong atau format kolom tidak sesuai!');
                    document.getElementById('btnSubmitImport').disabled = true;
                }
            };
            reader.readAsArrayBuffer(file);
        });
    }

    // 3. Kirim Data Bulk Store ke Server via AJAX Fetch
    function submitImportData() {
        if (parsedTransactions.length === 0) return;

        const btnSubmit = document.getElementById('btnSubmitImport');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        fetch("{{ route('spjs.bulk-store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                jenis_bos: currentJenisBos,
                transactions: parsedTransactions
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Gagal mengimpor: ' + data.message);
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Simpan Semua Transaksi';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi server.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Simpan Semua Transaksi';
        });
    }

    // 4. Export Table Ke Excel Client-Side
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
        const jenisBosName = "{{ request('jenis_bos') ? request('jenis_bos') : 'Semua_Jenis_BOS' }}";
        const fileName = `Rekap_SPJ_${jenisBosName}_{{ date('Ymd_His') }}.xlsx`;

        XLSX.writeFile(wb, fileName);
    }
</script>
@endpush