@extends('layouts.app')

@section('title', 'Kelola Jadwal Mengajar')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-3">
                <h4 class="mb-3 fw-bold text-gradient">
                    <i class="fas fa-tasks me-2"></i>Jadwal Mengajar Dosen
                </h4>
                <hr>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="input-group input-group-sm" style="max-width: 350px;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="custom-search-input" class="form-control border-start-0"
                            placeholder="Cari mata kuliah, dosen, hari...">
                    </div>
                    <a href="{{ route('admin.pengampuMataKuliah.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Penugasan
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 @if($pengampuMataKuliahs->isEmpty()) is-empty @endif"
                        id="pengampu-table" style="width:100%">
                        {{-- PERUBAHAN: Urutan kolom di header diubah --}}
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th>Dosen Pengampu</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">Hari</th>
                                <th class="text-center">Kelas</th>
                                <th>Jam</th>
                                <th class="text-center">Ruangan</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengampuMataKuliahs as $pengampu)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Dosen">{{ $pengampu->dosen->nama ?? 'N/A' }}</td>
                                    <td data-label="Mata Kuliah">
                                        <span class="fw-semibold">{{ $pengampu->mataKuliah->nama_mk ?? 'N/A' }}</span>
                                        <small class="d-block text-muted">{{ $pengampu->mataKuliah->kode_mk ?? '' }}
                                            ({{ $pengampu->mataKuliah->sks ?? '0' }} SKS)</small>
                                    </td>
                                    <td class="text-center fw-semibold"
                                        data-order="{{ ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6][$pengampu->hari] ?? 99 }}">
                                        {{ $pengampu->hari }}</td>
                                    <td data-label="Kelas" class="text-center">{{ $pengampu->kelas ?? '-' }}</td>
                                    <td class="text-center" width="12%" data-label="Jam">{{ \Carbon\Carbon::parse($pengampu->jam_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($pengampu->jam_selesai)->format('H:i') }}</td>
                                    <td data-label="Ruang" class="text-center"><span
                                            class="badge bg-secondary-subtle text-secondary-emphasis">{{ $pengampu->ruangan }}</span>
                                    </td>
                                    <td data-label="Aksi" class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.pengampuMataKuliah.edit', $pengampu->id) }}"
                                                class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit"><i
                                                    class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                data-url="{{ route('admin.pengampuMataKuliah.destroy', $pengampu->id) }}"
                                                data-name="{{ $pengampu->dosen->nama }} - {{ $pengampu->mataKuliah->nama_mk }}"
                                                data-bs-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p class="mb-0">Belum ada data penugasan dosen.</p>
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
            var table = $('#pengampu-table:not(.is-empty)').DataTable({
                dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
                paging: false, lengthChange: false, searching: true, ordering: true, info: false,
                order: [[3, 'asc'], [5, 'asc']], 
                language: { search: "", zeroRecords: "Data tidak ditemukan.", info: "Menampilkan _START_ - _END_ dari _TOTAL_ jadwal", infoEmpty: "Menampilkan 0 jadwal", paginate: { next: "›", previous: "‹" } },
                columnDefs: [
                    { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                    // PERUBAHAN: Indeks kolom Aksi disesuaikan menjadi 7
                    { orderable: false, searchable: false, targets: 7 }
                ],
                drawCallback: () => $('[data-bs-toggle="tooltip"]').each(function () { new bootstrap.Tooltip(this) })
            });
            $('#custom-search-input').on('keyup', function () { table.search(this.value).draw(); });

            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                const button = $(this); const url = button.data('url'); const name = button.data('name');
                Swal.fire({
                    title: 'Anda Yakin?', html: `Jadwal mengajar untuk <b>${name}</b> akan dihapus.`, icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url, type: 'POST', data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                            success: (response) => {
                                table.row(button.closest('tr')).remove().draw(false);
                                Swal.fire('Berhasil!', response.success, 'success');
                            },
                            error: (xhr) => Swal.fire('Gagal!', (xhr.responseJSON?.error || 'Terjadi kesalahan.'), 'error')
                        });
                    }
                });
            });
        });
    </script>
@endpush