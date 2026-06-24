@extends('layouts.app')

@section('title', 'Jadwal Kuliah')
@section('header_title', 'Jadwal Kuliah')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-calendar-alt me-2"></i>Jadwal Kuliah Anda
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari mata kuliah, dosen, hari...">
      </div>
      <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="fas fa-print me-1"></i> Cetak Jadwal
      </button>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($jadwalKuliahs->isEmpty()) is-empty @endif"
        id="mahasiswa-jadwal-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Mata Kuliah</th>
          <th>Dosen</th>
          <th class="text-center">Hari</th>
          <th>Jam</th>
          <th class="text-center">Kelas</th>
          <th class="text-center">Ruangan</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($jadwalKuliahs as $jadwal)
      <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td data-label="Mata Kuliah">
        <span class="fw-semibold">{{ $jadwal->mataKuliah->nama_mk ?? 'N/A' }}</span>
        <small class="d-block text-muted">{{ $jadwal->mataKuliah->kode_mk ?? '' }}
        ({{ $jadwal->mataKuliah->sks ?? '0' }} SKS)</small>
        </td>
        <td data-label="Dosen">{{ $jadwal->dosen->nama ?? 'N/A' }}</td>
        <td class="text-center fw-semibold"
        data-order="{{ ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6][$jadwal->hari] ?? 99 }}">
        {{ $jadwal->hari }}
        </td>
        <td data-label="Jam">
        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
        </td>
        <td data-label="Kelas" class="text-center">{{ $jadwal->kelas ?? '-' }}</td>
        <td data-label="Ruang" class="text-center">
        <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $jadwal->ruangan }}</span>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Tidak ada jadwal kuliah yang tersedia untuk Anda.</p>
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

    @media print {
    body * {
      visibility: hidden;
    }

    .card,
    .card * {
      visibility: visible;
    }

    .card {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      box-shadow: none;
      border: none;
    }

    .card-header .d-flex {
      display: none !important;
    }

    hr,
    .btn {
      display: none !important;
    }

    .table-responsive {
      overflow: visible !important;
    }

    .table {
      width: 100% !important;
    }

    .table td,
    .table th {
      color: #000 !important;
      background-color: #fff !important;
    }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    var table = $('#mahasiswa-jadwal-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false,
      order: [[3, 'asc'], [4, 'asc']],
      language: {
      search: "",
      zeroRecords: "Data tidak ditemukan.",
      info: "Menampilkan _START_ - _END_ dari _TOTAL_ jadwal",
      infoEmpty: "Menampilkan 0 jadwal",
      paginate: {
        next: ">", previous: "<"
      }
      },
      columnDefs: [
      { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
      ],
    });
    $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
    });
    });
  </script>
@endpush