@extends('layouts.app')

@section('title', 'Kelola Nilai Mahasiswa')
@section('header_title', 'Kelola Nilai Mahasiswa')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-3">
                <h4 class="mb-3 fw-bold text-gradient">
                    <i class="fas fa-clipboard-list me-2"></i>Kelola Nilai Mahasiswa
                </h4>
                <hr>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="input-group input-group-sm" style="max-width: 350px;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="custom-search-input" class="form-control border-start-0"
                            placeholder="Cari nama, NIM, mata kuliah...">
                    </div>
                    <a href="{{ route('dosen.kelolaNilaiMahasiswa.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Input Nilai Baru
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 @if($nilaiMahasiswas->isEmpty()) is-empty @endif"
                        id="nilai-dosen-table" style="width:100%">
                        <thead class="table-light">
                            {{-- Kolom sesuai permintaan Anda, tidak ada yang diubah --}}
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th>Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center">Nilai Akhir</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nilaiMahasiswas as $nilai)
                                <tr>
                                    <td class="text-center"></td>
                                    <td data-label="Mahasiswa">
                                        <span
                                            class="fw-semibold d-block">{{ optional($nilai->mahasiswa)->nama ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ optional($nilai->mahasiswa)->nim ?? '-' }}</small>
                                    </td>
                                    <td data-label="Mata Kuliah">
                                        <span class="fw-semibold">{{ optional($nilai->mataKuliah)->nama_mk ?? 'N/A' }}</span>
                                        <small class="d-block text-muted">{{ $nilai->kelas ?? '-' }}</small>
                                    </td>
                                    <td data-label="Kehadiran" class="text-center">{{ $nilai->kehadiran ?? '-' }}</td>
                                    <td data-label="Tugas" class="text-center">{{ $nilai->nilai_tugas ?? '-' }}</td>
                                    <td data-label="UTS" class="text-center">{{ $nilai->nilai_uts ?? '-' }}</td>
                                    <td data-label="UAS" class="text-center">{{ $nilai->nilai_uas ?? '-' }}</td>
                                    <td data-label="Akhir" class="text-center fw-bold">
                                        {{ number_format($nilai->nilai_angka, 2) }}</td>
                                    <td data-label="Grade" class="text-center fw-bold">{{ $nilai->nilai_huruf ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p class="mb-0">Anda belum menginput data nilai apapun.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .table th {
            font-weight: 600;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .table td {
            vertical-align: middle;
            font-size: .875rem;
        }

        @media (max-width: 991.98px) {
            .table thead {
                display: none;
            }

            .table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: .5rem;
            }

            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f0f0f0;
                padding: .75rem 1rem;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6c757d;
                margin-right: 1rem;
            }

            .table td:last-child {
                border-bottom: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#nilai-dosen-table:not(.is-empty)').DataTable({
                dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
                paging: false,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: false,
                order: [[1, 'asc']], // Urutkan berdasarkan nama mahasiswa
                language: {
                    search: "",
                    zeroRecords: "Data tidak ditemukan.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    paginate: { next: "›", previous: "‹" }
                },
                columnDefs: [
                    { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 }
                ]
            });
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush