@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-gradient"><i class="fas fa-edit me-2"></i>Edit Mata Kuliah</h4>
    </div>

    <form action="{{ route('admin.mataKuliahs.update', $mataKuliah->id) }}" method="POST" id="main-form">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-3"><h5 class="mb-0 fw-semibold"><i class="fas fa-book-open me-2 text-primary"></i>Detail Mata Kuliah</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label for="kode_mk" class="form-label required">Kode MK</label><input type="text" class="form-control" id="kode_mk" name="kode_mk" value="{{ old('kode_mk', $mataKuliah->kode_mk) }}" required></div>
                            <div class="col-md-8"><label for="nama_mk" class="form-label required">Nama Mata Kuliah</label><input type="text" class="form-control" id="nama_mk" name="nama_mk" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" required></div>
                            <div class="col-md-6"><label for="sks" class="form-label required">Jumlah SKS</label><input type="number" class="form-control" id="sks" name="sks" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6" required></div>
                            <div class="col-md-6"><label for="kelas" class="form-label required">Kelas</label><select id="kelas" name="kelas" class="form-select" required><option value="Reguler A" {{ old('kelas', $mataKuliah->kelas) == 'Reguler A' ? 'selected' : '' }}>Reguler A</option><option value="Reguler B" {{ old('kelas', $mataKuliah->kelas) == 'Reguler B' ? 'selected' : '' }}>Reguler B</option><option value="Reguler CK" {{ old('kelas', $mataKuliah->kelas) == 'Reguler CK' ? 'selected' : '' }}>Reguler CK</option><option value="Reguler CS" {{ old('kelas', $mataKuliah->kelas) == 'Reguler CS' ? 'selected' : '' }}>Reguler CS</option></select></div>
                            <div class="col-12"><label for="deskripsi" class="form-label">Deskripsi</label><textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white p-3"><h5 class="mb-0 fw-semibold"><i class="fas fa-cog me-2 text-primary"></i>Aksi</h5></div>
                    <div class="card-body">
                        <p class="text-muted">Pastikan semua data yang ditandai * telah terisi dengan benar.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submit-button"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                            <a href="{{ route('admin.mataKuliahs.index') }}" class="btn btn-outline-secondary">Batal</a>
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
    .text-gradient{background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));-webkit-background-clip:text;background-clip:text;color:transparent}
    .form-label.required::after{content:" *";color:var(--bs-danger)}
</style>
@endpush

@push('scripts')
<script>
    $('#main-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this), submitButton = $('#submit-button');
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        $('.form-control').removeClass('is-invalid');
        $.ajax({
            url: form.attr('action'), type: 'POST', data: form.serialize(),
            success: (response) => {
                Swal.fire({title:'Berhasil!',text:response.success,icon:'success',timer:2000,showConfirmButton:false})
                .then(() => window.location.href = "{{ route('admin.mataKuliahs.index') }}");
            },
            error: (xhr) => {
                submitButton.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Perubahan');
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
