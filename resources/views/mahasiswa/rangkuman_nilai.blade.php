@extends('layouts.app')

@section('title', 'Rangkuman Nilai Mahasiswa')
@section('header_title', 'Rangkuman Nilai')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <h4 class="mb-3 fw-bold text-gradient">
      <i class="fas fa-graduation-cap me-2"></i>Rangkuman Nilai (Transkrip)
      </h4>
      <hr>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="input-group input-group-sm" style="max-width: 350px;">
        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari kode atau nama mata kuliah...">
      </div>
      <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="fas fa-print me-1"></i> Cetak Transkrip
      </button>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($nilaiMahasiswas->isEmpty()) is-empty @endif"
        id="rangkuman-nilai-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Kode MK</th>
          <th>Mata Kuliah</th>
          <th class="text-center">SKS</th>
          <th class="text-center">Nilai</th>
          <th class="text-center">Angka</th>
          <th class="text-center">Mutu</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($nilaiMahasiswas as $nilai)
      <tr>
        <td class="text-center"></td>
        <td>{{ optional($nilai->mataKuliah)->kode_mk ?? '-' }}</td>
        <td>{{ optional($nilai->mataKuliah)->nama_mk ?? 'N/A' }}</td>
        <td class="text-center">{{ optional($nilai->mataKuliah)->sks ?? '0' }}</td>
        <td class="text-center fw-semibold">{{ $nilai->nilai_huruf ?? '-' }}</td>
        <td class="text-center">{{ number_format($nilai->mutu, 2) }}</td>
        <td class="text-center fw-bold">{{ number_format($nilai->sks_x_mutu, 2) }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data nilai.</p>
        </td>
      </tr>
      @endforelse
        </tbody>
        {{-- ==================================================================== --}}
        {{-- Bagian Footer Tabel (tfoot) Sesuai Permintaan --}}
        {{-- ==================================================================== --}}
        @if(!$nilaiMahasiswas->isEmpty())
      <tfoot class="table-light fw-bold" style="border-top: 2px solid #dee2e6;">
      <tr>
        <td colspan="5" class="text-center pe-4">TOTAL MUTU</td>
        <td colspan="2" class="text-center">{{ number_format($totalMutuKumulatif, 2) }}</td>
      </tr>
      <tr>
        <td colspan="5" class="text-center pe-4">IPK</td>
        <td colspan="2" class="text-center">{{ number_format($ipkKumulatif, 2) }}</td>
      </tr>
      </tfoot>
      @endif
      </table>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Style yang konsisten dengan halaman lain */
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

    .table tfoot td {
    font-size: .9rem;
    padding-top: 1rem;
    padding-bottom: 1rem;
    }

    @media (max-width: 991.98px) {

    .table thead,
    .table tfoot {
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

    .dataTables_paginate,
    .dataTables_info {
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
    var table = $('#rangkuman-nilai-table:not(.is-empty)').DataTable({
      // dom diubah untuk menghilangkan info dan pagination agar tfoot selalu terlihat
      dom: 'rt',
      paging: false, // Paging dinonaktifkan agar tfoot selalu terlihat
      info: false,   // Info dinonaktifkan
      lengthChange: false,
      searching: true,
      ordering: true,
      order: [], // Biarkan urutan default dari controller
      language: {
      search: "",
      zeroRecords: "Data tidak ditemukan.",
      },
      columnDefs: [
      // Penomoran otomatis
      { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
      ],
    });
    $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
    });
    });
  </script>
@endpush