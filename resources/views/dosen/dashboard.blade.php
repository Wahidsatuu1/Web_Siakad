@extends('layouts.app')

@section('title', 'Dashboard Dosen')
@section('header_title', 'Dashboard')

@section('content')
  <div class="container-fluid">
    {{-- Header Sambutan --}}
    <div class="mb-4">
    <h3 class="fw-bold text-gray-800">Selamat Datang, <span class="text-gradient">{{ $dosen->nama }}</span>!</h3>
    <p class="text-muted">Berikut ringkasan aktivitas dan informasi dari Sistem Akademik.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- PERUBAHAN UTAMA: Kartu Statistik Didesain Ulang --}}
    {{-- ========================================================== --}}
    <div class="row g-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card summary-card h-100 border-start-primary shadow-sm">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col me-2">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mata Kuliah Diajar</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMataKuliahDiajar }}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-book-open fa-2x text-gray-300"></i>
        </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card summary-card h-100 border-start-success shadow-sm">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col me-2">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Jadwal Ajar</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJadwalMengajar }}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
        </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card summary-card h-100 border-start-info shadow-sm">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col me-2">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Mahasiswa Dibimbing</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
          {{ $totalMahasiswaBimbingan }}
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
        </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card summary-card h-100 border-start-warning shadow-sm">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col me-2">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Nilai Telah Diinput</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalNilaiDiinput }}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>

    {{-- Konten Utama (Jadwal Mendatang dan Pengumuman) --}}
    <div class="row g-4 mt-0">
    {{-- Kolom Kiri --}}
    <div class="col-lg-7">
      {{-- Jadwal Mengajar Mendatang --}}
      <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-calendar-day me-2 text-primary"></i>Jadwal Mengajar Mendatang
        </h5>
      </div>
      <div class="card-body p-0">
        @if($upcomingSchedules->isEmpty())
      <div class="d-flex align-items-center justify-content-center p-4 text-center" style="min-height: 150px;">
      <p class="text-muted mb-0">Tidak ada jadwal mengajar dalam waktu dekat.</p>
      </div>
      @else
        <div class="list-group list-group-flush">
        @foreach($upcomingSchedules as $jadwal)
      <a href="{{ route('dosen.lihatJadwalMengajar') }}"
        class="list-group-item list-group-item-action border-0 py-3 px-4">
        <div class="d-flex w-100 justify-content-between">
        <h6 class="mb-1 fw-semibold text-dark">{{ optional($jadwal->mataKuliah)->nama_mk }}</h6>
        <small class="text-primary fw-semibold">{{ $jadwal->hari }}</small>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-1">
        <small class="text-muted">
        <i class="fas fa-clock fa-fw me-1"></i> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
        - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
        <span class="mx-2">|</span>
        <i class="fas fa-map-marker-alt fa-fw me-1"></i> {{ $jadwal->ruangan }}
        </small>
        <span class="badge bg-light text-dark">{{ $jadwal->kelas }}</span>
        </div>
      </a>
      @endforeach
        </div>
      @endif
      </div>
      </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-lg-5">
      {{-- Pengumuman --}}
      <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-bullhorn me-2 text-primary"></i>Pengumuman</h6>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
        @forelse($announcements as $announcement)
      <div class="list-group-item border-0 py-3 px-4">
        <div class="d-flex w-100 justify-content-between">
        <h6 class="mb-1 fw-semibold">{{ $announcement->title }}</h6>
        <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
        </div>
        <p class="mb-1 small text-muted">{{ $announcement->content }}</p>
      </div>
      @empty
      <div class="list-group-item border-0 text-center text-muted py-4">
        Tidak ada pengumuman saat ini.
      </div>
      @endforelse
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Menambahkan style yang digunakan di Dashboard Mahasiswa untuk konsistensi */
    .text-gradient {
    background: linear-gradient(135deg, #4e73df, #6f42c1);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .summary-card .card-body {
    padding: 1.5rem;
    }

    .border-start-primary {
    border-left: .25rem solid #4e73df !important;
    }

    .border-start-success {
    border-left: .25rem solid #1cc88a !important;
    }

    .border-start-info {
    border-left: .25rem solid #36b9cc !important;
    }

    .border-start-warning {
    border-left: .25rem solid #f6c23e !important;
    }

    .text-xs {
    font-size: .7rem;
    }

    .font-weight-bold {
    font-weight: 700 !important;
    }

    .text-gray-300 {
    color: #dddfeb !important;
    }

    .text-gray-800 {
    color: #5a5c69 !important;
    }
  </style>
@endpush