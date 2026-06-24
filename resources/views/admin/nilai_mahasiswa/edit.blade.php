@extends('layouts.app')

@section('title', 'Edit Nilai Mahasiswa')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4 fw-bold text-gradient"><i class="fas fa-edit me-2"></i>Edit Nilai Mahasiswa</h4>
        <form action="{{ route('admin.nilaiMahasiswas.update', $nilaiMahasiswa->id) }}" method="POST" id="main-form">
            @csrf
            @method('PUT')
            @include('admin.nilai_mahasiswa.form', ['nilaiMahasiswa' => $nilaiMahasiswa])
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: .375rem;
            border: 1px solid #dee2e6;
            height: 38px !important;
            padding: .375rem .75rem
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#mahasiswa_id, #mata_kuliah_id, #dosen_id, #kelas').select2({ theme: 'bootstrap-5' });
            $('#main-form').on('submit', function (e) { e.preventDefault(); const t = $(this), s = $("#submit-button"); s.prop("disabled", !0).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...'); $(".form-control, .form-select").removeClass("is-invalid"), $.ajax({ url: t.attr("action"), type: "POST", data: t.serialize(), success: t => { Swal.fire({ title: "Berhasil!", text: t.success, icon: "success", timer: 2e3, showConfirmButton: !1 }).then(() => { window.location.href = "{{ route('admin.nilaiMahasiswas.index') }}" }) }, error: t => { s.prop("disabled", !1).html('<i class="fas fa-save me-2"></i>Simpan Perubahan'); 422 === t.status ? Swal.fire("Gagal Validasi", "Mohon periksa kembali data yang Anda masukkan.", "error") : Swal.fire("Error!", "Terjadi kesalahan di server.", "error") } }) })
        });
    </script>
@endpush