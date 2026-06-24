@extends('layouts.app')

@section('title', 'Kelola Presensi Mahasiswa')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-user-check me-2"></i>Kelola Presensi Mahasiswa
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari mahasiswa, mata kuliah, tanggal...">
      </div>
      <a href="{{ route('admin.kelolaPresensi.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Presensi
      </a>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($presensiRecords->isEmpty()) is-empty @endif"
        id="presensi-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No</th>
          <th>Mahasiswa</th>
          <th>Mata Kuliah</th>
          <th>Tanggal</th>
          <th class="text-center">Status</th>
          <th class="text-center" width="10%">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($presensiRecords as $record)
        <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td data-label="Mahasiswa">
        <span class="fw-semibold d-block">{{ $record->mahasiswa->nama ?? 'N/A' }}</span>
        <small class="text-muted">{{ $record->mahasiswa->nim ?? '-' }}</small>
        </td>
        <td data-label="Mata Kuliah">
        <span class="fw-semibold">{{ $record->pengampuMataKuliah->mataKuliah->nama_mk ?? 'N/A' }}</span>
        <small class="d-block text-muted">{{ $record->pengampuMataKuliah->kelas ?? '-' }}</small>
        </td>
        <td data-label="Tanggal" data-order="{{ \Carbon\Carbon::parse($record->tanggal)->timestamp }}">
        {{{ \Carbon\Carbon::parse($record->tanggal)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}}
        </td>
        <td data-label="Status" class="text-center">
        {{-- PERUBAHAN: Logika Badge Status Disederhanakan --}}
        @php
        $isHadir = ($record->status_kehadiran == 'Hadir');
        $statusClass = $isHadir ? 'success' : 'danger';
        $statusText = $isHadir ? 'Hadir' : 'Tidak Hadir';
        @endphp
        <span
          class="badge bg-{{$statusClass}}-subtle text-{{$statusClass}}-emphasis rounded-pill">{{ $statusText }}</span>
        </td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('admin.kelolaPresensi.edit', $record->id) }}" class="btn btn-sm btn-outline-warning"
          data-bs-toggle="tooltip" title="Edit Status"><i class="fas fa-edit"></i></a>
          <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
          data-url="{{ route('admin.kelolaPresensi.destroy', $record->id) }}"
          data-name="{{ $record->mahasiswa->nama }} - {{ \Carbon\Carbon::parse($record->tanggal)->isoFormat('DD MMM') }}"
          data-bs-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt"></i></button>
        </div>
        </td>
        </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data presensi.</p>
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
    var table = $('#presensi-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false,
      order: [[3, 'asc']],
      language: {
      search: "",
      zeroRecords: "Data tidak ditemukan.",
      info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
      infoEmpty: "Menampilkan 0 data",
      paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
      { searchable: false, orderable: false, targets: 0 },
      { orderable: false, targets: 5 }
      ],
      drawCallback: () => $('[data-bs-toggle="tooltip"]').each(function () { new bootstrap.Tooltip(this) }),
      "fnDrawCallback": function (oSettings) {
      this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
      }
    });

    $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
    });

    // Script untuk tombol delete Anda tetap sama
    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();
      const button = $(this); const url = button.data('url'); const name = button.data('name');
      Swal.fire({
      title: 'Anda Yakin?',
      html: `Data presensi untuk <b>${name}</b> akan dihapus permanen.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonText: 'Batal',
      confirmButtonText: 'Ya, Hapus!'
      }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
        url: url,
        type: 'POST',
        data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
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