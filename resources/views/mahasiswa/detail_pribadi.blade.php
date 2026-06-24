@extends('layouts.app')

@section('title', 'Detail Pribadi Mahasiswa')
@section('header_title', 'Detail Pribadi')

@push('styles')
  <style>
    .card {
    border-radius: 12px;
    overflow: hidden;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
    border-radius: 0 !important;
    /* Bootstrap 5 might need this if card-header has its own radius */
    }

    /* --- STYLES FOR THE EDIT BUTTON (ADAPTED FROM YOUR EXAMPLE) --- */
    .btn {
    /* General button styling from your example */
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition, all 0.3s ease);
    /* Added fallback for --transition */
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    }

    .btn-primary {
    /* Primary button style from your example */
    background-color: var(--primary-color, #4361ee);
    /* Added fallback for --primary-color */
    color: white;
    }

    .btn-primary:hover {
    /* Hover state from your example */
    background-color: var(--primary-hover, #3a56d4);
    /* Added fallback for --primary-hover */
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);
    }

    /* --- END OF STYLES FOR THE EDIT BUTTON --- */


    .profile-section {
    margin-bottom: 1.5rem;
    }

    .profile-section:last-child {
    margin-bottom: 0;
    }

    .section-header {
    border-left: 4px solid var(--primary-color, #4361ee);
    }

    .section-header h5 i {
    color: var(--primary-color, #4361ee);
    }

    .info-card {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .info-item {
    display: flex;
    flex-wrap: wrap;
    padding: 0.85rem 0;
    border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
    border-bottom: none;
    }

    .info-label {
    flex: 0 0 200px;
    font-weight: 500;
    color: #555;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    }

    .info-label i {
    color: var(--primary-color, #4361ee);
    width: 20px;
    text-align: center;
    }

    .info-value {
    flex: 1;
    font-weight: 400;
    color: #333;
    font-size: 0.8rem;
    }

    .status-badge {
    display: inline-block;
    padding: 0.4em 0.75em;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 500;
    line-height: 1;
    }

    .status-aktif {
    background-color: #d1e7dd;
    color: #0f5132;
    }

    .status-nonaktif,
    .status-drop_out,
    .status-mengundurkan_diri {
    background-color: #f8d7da;
    color: #842029;
    }

    .status-cuti {
    background-color: #fff3cd;
    color: #664d03;
    }

    .status-lulus {
    background-color: #cff4fc;
    color: #055160;
    }

    .status-none,
    .status- {
    background-color: #e2e3e5;
    color: #495057;
    }


    @media (max-width: 992px) {
    .info-label {
      flex: 0 0 40%;
    }
    }

    @media (max-width: 768px) {
    .info-label {
      flex: 0 0 100%;
      margin-bottom: 0.35rem;
    }

    .info-value {
      flex: 0 0 100%;
      padding-left: 0;
      /* Adjusted: padding-left was empty */
    }

    .info-card {
      padding: 1rem;
    }
    }

    /* Responsive for the new button text */
    @media (max-width: 576px) {
    .btn-text {
      display: none;
    }

    .btn i {
      margin-right: 0 !important;
      /* Ensure no margin if only icon */
    }
    }
  </style>
@endpush
@section('content')
  <div class="container-fluid px-0 px-md-3">
    <div class="card shadow-sm border-0">
    <div class="card-header bg-light text-primarypy-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 fw-bold text-primary">
      <i class="fas fa-user-graduate me-2"></i>Informasi Pribadi dan Akademik
      </h6>
      <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-primary">
      <i class="fas fa-edit"></i> <span class="btn-text">Edit Detail Pribadi</span>
      </a>
    </div>

    <div class="card-body">
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

      <div class="row g-4">
      <!-- Data Pribadi -->
      <div class="col-lg-6">
        <div class="profile-section">
        <div class="section-header bg-light px-3 py-2 mb-3 rounded">
          <h5 class="m-0 text-primary">
          <i class="fas fa-id-card me-2"></i>Data Pribadi
          </h5>
        </div>

        <div class="info-card">
          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-user me-2"></i>Nama Lengkap
          </div>
          <div class="info-value">{{ $mahasiswa->user->name ?? $mahasiswa->nama }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-id-badge me-2"></i>NIM
          </div>
          <div class="info-value">{{ $mahasiswa->nim }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-envelope me-2"></i>Email
          </div>
          <div class="info-value">{{ $mahasiswa->user->email ?? $mahasiswa->email }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-phone me-2"></i>Telepon
          </div>
          <div class="info-value">{{ $mahasiswa->telepon ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-birthday-cake me-2"></i>Tanggal Lahir
          </div>
          <div class="info-value">
            {{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->isoFormat('D MMMM YYYY') : '-' }}
          </div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-map-marker-alt me-2"></i>Alamat
          </div>
          <div class="info-value">{{ $mahasiswa->alamat ?? '-' }}</div>
          </div>
        </div>
        </div>
      </div>

      <!-- Data Akademik -->
      <div class="col-lg-6">
        <div class="profile-section">
        <div class="section-header bg-light px-3 py-2 mb-3 rounded">
          <h5 class="m-0 text-primary">
          <i class="fas fa-graduation-cap me-2"></i>Data Akademik
          </h5>
        </div>

        <div class="info-card">
          <div class="info-item">
          <div class="info-label">
            <i class="bi bi-calendar-event me-2"></i>Angkatan
          </div>
          <div class="info-value">{{ $mahasiswa->angkatan }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-university me-2"></i>Program Studi
          </div>
          <div class="info-value">{{ $mahasiswa->program_studi ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-chalkboard-teacher me-2"></i>Prog. Perkuliahan
          </div>
          <div class="info-value">{{ $mahasiswa->prog_perkuliahan ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="bi bi-easel2 me-2"></i>Kelas
          </div>
          <div class="info-value">{{ $mahasiswa->kelas ?? '-' }}</div>
          </div>

          <div class="info-item">
          <div class="info-label">
            <i class="fas fa-user-check me-2"></i>Status Mahasiswa
          </div>
          <div class="info-value">
            <span
            class="status-badge status-{{ strtolower(str_replace(' ', '_', $mahasiswa->status_mahasiswa ?? 'none')) }}">
            {{ $mahasiswa->status_mahasiswa ?? '-' }}
            </span>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection