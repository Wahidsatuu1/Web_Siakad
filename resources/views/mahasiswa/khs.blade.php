@extends('layouts.app')

@section('title', 'Kartu Hasil Studi (KHS)')
@section('header_title', 'Kartu Hasil Studi (KHS)')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-file-invoice me-2"></i>Kartu Hasil Studi (Transkrip Nilai)
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari kode atau nama mata kuliah...">
      </div>
      <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="fas fa-print me-1"></i> Cetak KHS
      </button>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($nilaiMahasiswas->isEmpty()) is-empty @endif"
        id="khs-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Kode MK</th>
          <th>Mata Kuliah</th>
          <th class="text-center">SKS</th>
          <th class="text-center">Kelas</th>
          <th class="text-center">Kehadiran</th> {{-- <-- PERUBAHAN HEADER --}} <th class="text-center">Tugas</th>
          <th class="text-center">UTS</th>
          <th class="text-center">UAS</th>
          <th class="text-center">Nilai Angka</th>
          <th class="text-center">Grade</th>
          <th class="text-center">Mutu</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($nilaiMahasiswas as $nilai)
      <tr>
        <td class="text-center"></td>
        <td data-label="Kode MK">{{ $nilai->mataKuliah->kode_mk ?? '-' }}</td>
        <td data-label="Mata Kuliah">
        <span class="fw-semibold">{{ $nilai->mataKuliah->nama_mk ?? 'N/A' }}</span>
        </td>
        <td data-label="SKS" class="text-center">{{ $nilai->mataKuliah->sks ?? '0' }}</td>
        <td data-label="Kelas" class="text-center">{{ $nilai->kelas ?? '-' }}</td>

        {{-- VVV PERUBAHAN UTAMA DI SINI VVV --}}
        <td data-label="Kehadiran" class="text-center">{{ $nilai->kehadiran ?? '0' }}</td>

        <td data-label="Tugas" class="text-center">{{ $nilai->nilai_tugas ?? '-' }}</td>
        <td data-label="UTS" class="text-center">{{ $nilai->nilai_uts ?? '-' }}</td>
        <td data-label="UAS" class="text-center">{{ $nilai->nilai_uas ?? '-' }}</td>
        <td data-label="Nilai Angka" class="text-center fw-bold">{{ number_format($nilai->nilai_angka, 2) }}</td>
        <td data-label="Grade" class="text-center fw-semibold">{{ $nilai->nilai_huruf ?? '-' }}</td>
        <td data-label="Mutu" class="text-center fw-bold">{{ number_format($nilai->mutu, 2) }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="12" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data nilai yang tersedia untuk Anda.</p>
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
    /* Menggunakan style yang sama dengan Jadwal Kuliah untuk konsistensi */
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
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    var table = $('#khs-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false,
      // Urutkan berdasarkan Kelas (kolom ke-5), lalu Mata Kuliah (kolom ke-3)
      order: [[4, 'asc'], [2, 'asc']],
      language: {
      search: "",
      zeroRecords: "Data tidak ditemukan.",
      info: "Menampilkan _START_ - _END_ dari _TOTAL_ nilai",
      infoEmpty: "Menampilkan 0 nilai",
      paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
      // Penomoran otomatis yang cerdas
      { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
      ],
    });
    $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
    });
    });
  </script>
@endpush