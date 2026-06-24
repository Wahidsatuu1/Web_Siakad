@extends('layouts.app')

@section('title', 'Kelola Data Dosen')
@section('header_title', 'Kelola Data Dosen')

@section('content')
  <div class="container-fluid">
    {{-- Notifikasi Session --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Card Utama --}}
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-chalkboard-teacher me-2"></i>Data Dosen
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari nama, NIDN, atau email...">
      </div>
      <a href="{{ route('admin.dosens.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Dosen Baru
      </a>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($dosens->isEmpty()) is-empty @endif" id="dosens-table"
        style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Nama Dosen</th>
          <th>NIDN</th>
          <th>Program Studi</th>
          <th class="text-center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($dosens as $dosen)
      <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td data-label="Nama Dosen">
        <div class="d-flex align-items-center">
        <div class="avatar-sm me-3">{{ strtoupper(substr($dosen->nama, 0, 1)) }}</div>
        <div>
        <span class="fw-semibold d-block">{{ $dosen->nama }}</span>
        <small class="text-muted">{{ $dosen->email }}</small>
        </div>
        </div>
        </td>
        <td data-label="NIDN">{{ $dosen->nidn ?? '-' }}</td>
        <td data-label="Prodi">{{ $dosen->prodi ?? '-' }}</td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('admin.dosens.edit', $dosen->id) }}" class="btn btn-sm btn-outline-warning"
        data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
        <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
        data-url="{{ route('admin.dosens.destroy', $dosen->id) }}" data-name="{{ $dosen->nama }}"
        data-bs-toggle="tooltip" title="Hapus">
        <i class="fas fa-trash-alt"></i>
        </button>
        </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data dosen.</p>
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

    .avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-color);
    color: white;
    font-weight: 600;
    }

    @media (max-width: 768px) {
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
    var table = $('#dosens-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false,
      order: [[1, 'asc']],
      language: { search: "", zeroRecords: "Data tidak ditemukan.", info: "Menampilkan _START_ - _END_ dari _TOTAL_ dosen", infoEmpty: "Menampilkan 0 dosen", paginate: { next: "›", previous: "‹" } },
      columnDefs: [
      { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
      { orderable: false, searchable: false, targets: 4 }
      ],
      drawCallback: () => $('[data-bs-toggle="tooltip"]').each(function () { new bootstrap.Tooltip(this) })
    });
    $('#custom-search-input').on('keyup', function () { table.search(this.value).draw(); });

    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();
      const button = $(this);
      const url = button.data('url');
      const name = button.data('name');

      Swal.fire({
      title: 'Anda Yakin?', html: `Data untuk <b>${name}</b> dan akun login terkait akan dihapus.`, icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!'
      }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
        url: url, type: 'POST', data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function (response) {
          table.row(button.closest('tr')).remove().draw(false);
          Swal.fire('Berhasil!', response.success, 'success');
        },
        error: (xhr) => Swal.fire('Gagal!', (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Terjadi kesalahan.', 'error')
        });
      }
      });
    });
    });
  </script>
@endpush