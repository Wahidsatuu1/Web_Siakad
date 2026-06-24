@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        {{-- Header Sambutan --}}
        <div class="mb-4">
            <h3 class="fw-bold">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
            <p class="text-muted">Berikut ringkasan dari Sistem Informasi Akademik.</p>
        </div>

        {{-- Kartu Statistik --}}
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <h5 class="stat-card-title">Total Pengguna</h5>
                        <p class="stat-card-value">{{ $totalUsers ?? '0' }}</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <h5 class="stat-card-title">Total Dosen</h5>
                        <p class="stat-card-value">{{ $totalDosen ?? '0' }}</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <h5 class="stat-card-title">Total Mahasiswa</h5>
                        <p class="stat-card-value">{{ $totalMahasiswa ?? '0' }}</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-card-body">
                        <h5 class="stat-card-title">Mata Kuliah</h5>
                        <p class="stat-card-value">{{ $totalMataKuliah ?? '0' }}</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            {{-- Kolom Kiri: Grafik Jurusan --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-chart-bar me-2 text-primary"></i>Grafik Mahasiswa Per Jurusan</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 350px;">
                            <canvas id="chartJurusan"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan Data --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-primary"></i>Ringkasan Data</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach ($dataSummary as $jenis => $data)
                                        <tr>
                                            <td><span class="fw-semibold">{{ $jenis }}</span></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">
                                                    {{ $data['count'] ?? '0' }}
                                                </span>
                                            </td>
                                            <td class="text-end text-muted small">
                                                @if ($data['last_updated'])
                                                    {{ \Carbon\Carbon::parse($data['last_updated'])->diffForHumans() }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris Baru: Form Pengumuman --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-bullhorn me-2 text-primary"></i>Buat Pengumuman</h5>
                    </div>
                    <div class="card-body">
                        <form id="announcement-form" action="{{ route('admin.announcements.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="announcement_title" class="form-label">Judul</label>
                                        <input type="text" id="announcement_title" name="title" class="form-control" placeholder="Judul pengumuman...">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="target_role" class="form-label">Tujukan Untuk</label>
                                        <select id="target_role" name="target_role" class="form-select">
                                            <option value="Semua" selected>Semua Pengguna</option>
                                            <option value="Dosen">Dosen</option>
                                            <option value="Mahasiswa">Mahasiswa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="announcement_content" class="form-label">Isi Pengumuman</label>
                                        <textarea id="announcement_content" name="content" class="form-control" rows="4" placeholder="Ketik pengumuman di sini..."></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-4" id="submit-announcement">
                                <i class="fas fa-paper-plane me-2"></i>Posting Pengumuman
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            background: var(--white-bg);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card-body {
            flex-grow: 1;
        }

        .stat-card-title {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }

        .stat-card-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            opacity: 0.15;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartJurusan').getContext('2d');
            
            // Mengambil data dari variabel compact controller
            const labels = {!! json_encode($labels) !!};
            const totals = {!! json_encode($totals) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Mahasiswa',
                        data: totals,
                        backgroundColor: 'rgba(67, 97, 238, 0.8)',
                        borderColor: '#4361ee',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Script AJAX Pengumuman
            $('#announcement-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const submitButton = $('#submit-announcement');

                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memposting...');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        submitButton.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Posting Pengumuman');
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.success,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        form.trigger('reset');
                    },
                    error: function (xhr) {
                        submitButton.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Posting Pengumuman');
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                const input = $(`#announcement_${key}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush