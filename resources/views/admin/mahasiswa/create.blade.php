@extends('layouts.app')
@section('title', 'Tambah Mahasiswa Baru')


@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold text-gradient"><i class="fas fa-user-plus me-2"></i>Tambah Mahasiswa Baru</h4>
        </div>

        <form action="{{ route('admin.mahasiswas.store') }}" method="POST" id="main-form">
            @csrf
            @include('admin.mahasiswa.form', ['mahasiswa' => new App\Models\Mahasiswa])
        </form>
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

        .form-label.required::after {
            content: " *";
            color: var(--bs-danger);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $('#main-form').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const submitButton = $('#submit-button');

            submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
            $('.form-control').removeClass('is-invalid');

            $.ajax({
                url: form.attr('action'), type: 'POST', data: form.serialize(),
                success: (response) => {
                    Swal.fire({ title: 'Berhasil!', text: response.success, icon: 'success', timer: 2000, showConfirmButton: false })
                        .then(() => window.location.href = "{{ route('admin.mahasiswas.index') }}");
                },
                error: (xhr) => {
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