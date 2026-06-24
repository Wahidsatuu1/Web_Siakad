@extends('layouts.app')

@section('title', 'Edit Presensi Mahasiswa')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4 fw-bold text-gradient"><i class="fas fa-edit me-2"></i>Edit Presensi Mahasiswa</h4>
    <form action="{{ route('admin.kelolaPresensi.update', $presensiMahasiswa->id) }}" method="POST" id="main-form">
    @csrf
    @method('PUT')
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
      <h5 class="mb-0 fw-semibold"><i class="fas fa-calendar-check me-2 text-primary"></i>Detail Presensi</h5>
      </div>
      <div class="card-body">
      <div class="row g-3">
        <div class="col-12">
        <label class="form-label">Mahasiswa</label>
        <p class="form-control-plaintext"><strong>{{ $presensiMahasiswa->mahasiswa->nama ?? 'N/A' }}</strong>
          ({{ $presensiMahasiswa->mahasiswa->nim ?? 'N/A' }})</p>
        </div>
        <div class="col-12">
        <label class="form-label">Mata Kuliah</label>
        <p class="form-control-plaintext">{{ $presensiMahasiswa->pengampuMataKuliah->mataKuliah->nama_mk ?? 'N/A' }}
          - {{ $presensiMahasiswa->pengampuMataKuliah->kelas ?? 'N/A' }}</p>
        </div>
        <div class="col-md-6">
        <label class="form-label">Tanggal</label>
        <p class="form-control-plaintext">
          {{ \Carbon\Carbon::parse($presensiMahasiswa->tanggal)->isoFormat('dddd, DD MMMM YYYY') }}
        </p>
        </div>
        <div class="col-md-6">
        <label for="status_kehadiran" class="form-label required">Ubah Status Kehadiran</label>
        <select id="status_kehadiran" name="status_kehadiran" class="form-select" required>
          <option value="Hadir" {{ old('status_kehadiran', $presensiMahasiswa->status_kehadiran) == 'Hadir' ? 'selected' : '' }}>Hadir</option>
          <option value="Izin" {{ old('status_kehadiran', $presensiMahasiswa->status_kehadiran) == 'Izin' ? 'selected' : '' }}>Izin</option>
          <option value="Sakit" {{ old('status_kehadiran', $presensiMahasiswa->status_kehadiran) == 'Sakit' ? 'selected' : '' }}>Sakit</option>
          <option value="Alpha" {{ old('status_kehadiran', $presensiMahasiswa->status_kehadiran) == 'Alpha' ? 'selected' : '' }}>Alpha</option>
        </select>
        </div>
        <div class="col-12">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea class="form-control" id="keterangan" name="keterangan"
          rows="3">{{ old('keterangan', $presensiMahasiswa->keterangan) }}</textarea>
        </div>
      </div>
      <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('admin.kelolaPresensi.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary" id="submit-button"><i class="fas fa-save me-2"></i>Simpan
        Perubahan</button>
      </div>
      </div>
    </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
    $('#main-form').on('submit', function (e) {
      e.preventDefault();
      const form = $(this);
      const submitButton = $("#submit-button");
      submitButton.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
      $(".form-control, .form-select").removeClass("is-invalid");

      $.ajax({
      url: form.attr("action"),
      type: "POST", // Method override (PUT) akan ditangani oleh Blade
      data: form.serialize(),
      success: function (response) {
        Swal.fire({
        title: "Berhasil!",
        text: response.success,
        icon: "success",
        timer: 2000,
        showConfirmButton: false
        }).then(() => {
        window.location.href = "{{ route('admin.kelolaPresensi.index') }}";
        });
      },
      error: function (xhr) {
        submitButton.prop("disabled", false).html('<i class="fas fa-save me-2"></i>Simpan Perubahan');
        Swal.fire("Error!", "Terjadi kesalahan. Pastikan semua data terisi.", "error");
      }
      });
    });
    });
  </script>
@endpush