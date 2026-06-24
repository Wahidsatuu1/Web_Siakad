@extends('layouts.app')
@section('title', 'Tambah Dosen Baru')
@section('header_title', 'Tambah Dosen Baru')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-gradient"><i class="fas fa-plus-circle me-2"></i>Tambah Dosen Baru</h4>
    </div>

    <form action="{{ route('admin.dosens.store') }}" method="POST" id="create-dosen-form">
    @csrf
    <div class="row g-4">
      <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-id-card me-2 text-primary"></i>Profil Dosen</h5>
        </div>
        <div class="card-body">
        <div class="row g-3">
          <div class="col-md-12"><label for="nama" class="form-label required">Nama Lengkap & Gelar</label><input
            type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
            placeholder="Contoh: Dr. Budi Santoso, M.Kom." required></div>
          <div class="col-md-6"><label for="nidn" class="form-label">NIDN</label><input type="text"
            class="form-control" id="nidn" name="nidn" value="{{ old('nidn') }}"
            placeholder="Nomor Induk Dosen Nasional"></div>
          <div class="col-md-6"><label for="prodi" class="form-label">Program Studi</label><input type="text"
            class="form-control" id="prodi" name="prodi" value="{{ old('prodi') }}"
            placeholder="Contoh: Teknik Informatika"></div>
        </div>
        </div>
      </div>
      <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-user-lock me-2 text-primary"></i>Akun Login Dosen</h5>
        </div>
        <div class="card-body">
        <div class="row g-3">
          <div class="col-md-12"><label for="email" class="form-label required">Email</label><input type="email"
            class="form-control" id="email" name="email" value="{{ old('email') }}"
            placeholder="Gunakan email aktif" required></div>
          <div class="col-md-6"><label for="password" class="form-label required">Password</label><input
            type="password" class="form-control" id="password" name="password" required></div>
          <div class="col-md-6"><label for="password_confirmation" class="form-label required">Konfirmasi
            Password</label><input type="password" class="form-control" id="password_confirmation"
            name="password_confirmation" required></div>
        </div>
        </div>
      </div>
      </div>
      <div class="col-lg-4">
      <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
        <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-cog me-2 text-primary"></i>Aksi</h5>
        </div>
        <div class="card-body">
        <p class="text-muted">Pastikan semua data yang ditandai * telah terisi dengan benar.</p>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" id="submit-button"><i
            class="fas fa-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.dosens.index') }}" class="btn btn-secondary">Batal</a>
        </div>
        </div>
      </div>
      </div>
    </div>
    </form>
  </div>
@endsection

@push('styles')
  <style>
    .text-gradient {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent
    }

    .form-label.required::after {
    content: " *";
    color: var(--bs-danger)
    }
  </style>
@endpush

@push('scripts')
  <script>
    $('#create-dosen-form').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const submitButton = $('#submit-button');

    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
    $('.form-control').removeClass('is-invalid');

    $.ajax({
      url: form.attr('action'), type: 'POST', data: form.serialize(),
      success: function (response) {
      Swal.fire({ title: 'Berhasil!', text: response.success, icon: 'success', timer: 2000, showConfirmButton: false })
        .then(() => window.location.href = "{{ route('admin.dosens.index') }}");
      },
      error: function (xhr) {
      submitButton.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan');
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        $.each(errors, (key, value) => $(`#${key}`).addClass('is-invalid'));
        Swal.fire('Gagal Validasi', 'Mohon periksa kembali data yang Anda masukkan.', 'error');
      } else {
        Swal.fire('Error!', 'Terjadi kesalahan di server.', 'error');
      }
      }
    });
    });
  </script>
@endpush