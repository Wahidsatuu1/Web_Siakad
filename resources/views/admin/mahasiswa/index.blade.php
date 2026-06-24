@extends('layouts.app')

@section('title', 'Kelola Data Mahasiswa')

@section('content')
  <div class="container-fluid">
    {{-- Notifikasi untuk hasil import --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->has('import_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h4 class="alert-heading">Impor Gagal!</h4>
    <pre class="mb-0">{{ $errors->first('import_error') }}</pre>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-user-graduate me-2"></i>Data Mahasiswa
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari nama, NIM, atau email...">
      </div>
      <div class="d-flex gap-2">
        {{-- Tombol Import --}}
        {{-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
        data-bs-target="#importModal">
        <i class="fas fa-file-excel me-1"></i> Import Excel
        </button> --}}
        <a href="{{ route('admin.mahasiswas.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Mahasiswa
        </a>
      </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($mahasiswas->isEmpty()) is-empty @endif"
        id="mahasiswas-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Nama Mahasiswa</th>
          <th>NIM</th>
          <th>Jurusan</th>
          <th>Angkatan</th>
          <th class="text-center">Kelas</th>
          <th class="text-center">Status</th>
          <th class="text-center" width="15%">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($mahasiswas as $mahasiswa)
      <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td data-label="Mahasiswa">
        <div class="d-flex align-items-center">
        <div class="avatar-sm me-3">{{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}</div>
        <div>
        <span class="fw-semibold d-block">{{ $mahasiswa->nama }}</span>
        <small class="text-muted">{{ $mahasiswa->email }}</small>
        </div>
        </div>
        </td>
        <td data-label="NIM">{{ $mahasiswa->nim }}</td>
        <td data-label="Jurusan">{{ $mahasiswa->jurusan }}</td>
        <td data-label="Angkatan">{{ $mahasiswa->angkatan }}</td>
        <td data-label="Kelas" class="text-center">{{ $mahasiswa->kelas ?? '-' }}</td>
        <td data-label="Status" class="text-center">
        <span class="badge bg-success-subtle text-success-emphasis">{{ $mahasiswa->status_mahasiswa }}</span>
        </td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('admin.mahasiswas.edit', $mahasiswa->id) }}" class="btn btn-sm btn-outline-warning"
        data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
        <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
        data-url="{{ route('admin.mahasiswas.destroy', $mahasiswa->id) }}"
        data-name="{{ $mahasiswa->nama }}" data-bs-toggle="tooltip" title="Hapus"><i
          class="fas fa-trash-alt"></i></button>
        </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data mahasiswa.</p>
        </td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>
    </div>
    </div>
  </div>

  <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="importModalLabel">Import Data Mahasiswa</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.mahasiswas.import') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="alert alert-info">
        Silakan unduh template di bawah ini untuk memastikan format data sesuai. Pastikan header dan urutan kolom
        tidak diubah.
        </div>
        <a href="{{ route('admin.mahasiswas.import.template') }}" class="btn btn-sm btn-outline-success mb-3">
        <i class="fas fa-file-download me-1"></i> Unduh Template Excel
        </a>
        <div class="mb-3">
        <label for="file" class="form-label">Pilih File Excel</label>
        <input class="form-control" type="file" id="file" name="file" required accept=".xlsx, .xls, .csv">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
        <i class="fas fa-upload me-1"></i> Import Data
        </button>
      </div>
      </form>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  {{-- Kode CSS Anda yang sudah ada --}}
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

    /* Style untuk pesan error import */
    .alert pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: .875em;
    color: inherit;
    }
  </style>
@endpush

@push('scripts')
  {{-- Kode Javascript Anda yang sudah ada --}}
  <script>
    $(document).ready(function () {
    var table = $('#mahasiswas-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false,
      order: [[1, 'asc']],
      language: { search: "", zeroRecords: "Data tidak ditemukan.", info: "Menampilkan _START_ - _END_ dari _TOTAL_ mahasiswa", infoEmpty: "Menampilkan 0 mahasiswa", paginate: { next: "›", previous: "‹" } },
      columnDefs: [
      { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
      { orderable: false, searchable: false, targets: 7 }
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