@extends('layouts.app')

@section('title', 'Ubah Password Admin')
@section('header_title', 'Ubah Password')

@section('content')
  <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      {{-- Menerapkan struktur Card yang konsisten --}}
      <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h4 class="mb-0 fw-bold text-gradient">
        <i class="fas fa-key me-2"></i>Ubah Password
        </h4>
      </div>
      <div class="card-body p-4">

        {{-- Menampilkan Notifikasi Sukses --}}
        @if (session('success'))
      <div class="alert alert-success d-flex align-items-center gap-2">
      <i class="fas fa-check-circle"></i>
      <span>{{ session('success') }}</span>
      </div>
      @endif

        {{-- Menampilkan Notifikasi Error Validasi --}}
        @if ($errors->any())
      <div class="alert alert-danger">
      <div class="d-flex">
        <i class="fas fa-exclamation-circle me-2 mt-1"></i>
        <div>
        <span class="fw-semibold">Terdapat kesalahan validasi:</span>
        <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
        </ul>
        </div>
      </div>
      </div>
      @endif

        <form action="{{ route('admin.changePassword') }}" method="POST">
        @csrf
        {{-- Menggunakan kelas Bootstrap untuk layout yang lebih baik --}}
        <div class="mb-3">
          <label for="new_password" class="form-label">Password Baru</label>
          <input type="password" class="form-control" id="new_password" name="new_password" required
          placeholder="Masukkan password baru...">
        </div>
        <div class="mb-3">
          <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
          <input type="password" class="form-control" id="new_password_confirmation"
          name="new_password_confirmation" required placeholder="Ketik ulang password baru Anda...">
        </div>
        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-2"></i>Simpan Perubahan Password
          </button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Style ini akan menata tampilan form dan notifikasi agar lebih modern */
    .text-gradient {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .form-label {
    font-weight: 600;
    color: #495057;
    }

    .form-control {
    border-radius: .5rem;
    /* Sudut lebih melengkung */
    padding: .75rem 1rem;
    }

    .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(74, 99, 232, 0.25);
    }

    .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    padding: .75rem 1.5rem;
    font-weight: 600;
    border-radius: .5rem;
    }

    .btn-primary:hover {
    background-color: #3A53D3;
    border-color: #3A53D3;
    }

    .alert {
    border-left-width: 5px;
    border-radius: .5rem;
    }
  </style>
@endpush