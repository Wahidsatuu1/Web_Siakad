@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('header_title', 'Dashboard')@section('content')<div class="container-fluid">
        {{-- Header Sambutan dan Notifikasi --}}
        <h4 class="h3 mb-4 fw-bold" style="color: #343A40;">
            Selamat Datang, <span class="text-gradient">{{ $mahasiswa->nama }}</span>!
        </h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Kartu Statistik Utama (Summary Cards) --}}
        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card summary-card h-100 border-start-primary shadow-sm">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">IPK (Kumulatif)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format(end($chartIPKValues), 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card summary-card h-100 border-start-success shadow-sm">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total SKS
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalSksKrs }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card summary-card h-100 border-start-info shadow-sm">
                    <div class="card-body">
                        <div class="row no-gutters align-items:center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kelas / Semester Aktif
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mahasiswa->kelas ?? 'N/A' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama (Grafik dan Jadwal) --}}
        <div class="row">
            {{-- Kolom Kiri - Grafik dan Pengumuman --}}
            <div class="col-xl-8 col-lg-7">
                {{-- Grafik Perkembangan Akademik --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Perkembangan Akademik (IPS & IPK)</h6>
                    </div>
                    <div class="card-body">
                        @if(empty($chartLabels) || count($chartLabels) == 0)
                            <div class="text-center py-5">
                                <i class="fas fa-chart-line text-muted fa-3x mb-3"></i>
                                <p class="text-muted">Belum ada data nilai untuk menampilkan grafik.</p>
                            </div>
                        @else
                            <div class="chart-container" style="position: relative; height:320px;">
                                <canvas id="ipkIpsChart"></canvas>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Jadwal Kuliah Hari Ini</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <tbody>
                                    @forelse($upcomingClasses as $jadwal)
                                        <tr>
                                            <td class="ps-3">
                                                <span
                                                    class="fw-bold d-block">{{ optional($jadwal->mataKuliah)->nama_mk }}</span>
                                                <small class="text-muted">{{ optional($jadwal->dosen)->nama }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                                </span>
                                            </td>
                                            <td class="text-center pe-3">
                                                <span class="badge bg-info-subtle text-info-emphasis">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $jadwal->ruangan }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Tidak ada jadwal kuliah hari
                                                ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('mahasiswa.lihatJadwalKuliah') }}" class="small">Lihat Jadwal Lengkap <i
                                class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan - Nilai Terbaru dan Akses Cepat --}}
            <div class="col-xl-4 col-lg-5">
                {{-- Nilai Terbaru --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Nilai Terbaru</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recentGrades as $nilai)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold d-block">{{ optional($nilai->mataKuliah)->nama_mk }}</span>
                                        <small class="text-muted">{{ optional($nilai->mataKuliah)->kode_mk }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill fs-6">{{ $nilai->nilai_huruf }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-3">Belum ada data nilai.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('mahasiswa.lihatKHS') }}" class="small">Lihat Semua Nilai <i
                                class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pengumuman</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($announcements as $announcement)
                                <div class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $announcement->title }}</h6>
                                        <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 small text-muted">{{ $announcement->content }}</p>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    Tidak ada pengumuman saat ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Akses Cepat --}}
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Akses Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('mahasiswa.lihatJadwalKuliah') }}"
                                class="list-group-item list-group-item-action"><i
                                    class="fas fa-calendar-alt fa-fw me-2 text-info"></i>Jadwal Kuliah</a>
                            <a href="{{ route('mahasiswa.lihatKRS') }}" class="list-group-item list-group-item-action"><i
                                    class="fas fa-tasks fa-fw me-2 text-success"></i>Kartu Rencana Studi</a>
                            <a href="{{ route('mahasiswa.lihatKHS') }}" class="list-group-item list-group-item-action"><i
                                    class="fas fa-file-invoice fa-fw me-2 text-primary"></i>Kartu Hasil Studi</a>
                            <a href="{{ route('mahasiswa.lihatRangkumanNilai') }}"
                                class="list-group-item list-group-item-action"><i
                                    class="fas fa-graduation-cap fa-fw me-2 text-warning"></i>Rangkuman Nilai</a>
                            <a href="{{ route('mahasiswa.presensi.form') }}"
                                class="list-group-item list-group-item-action"><i
                                    class="fas fa-user-check fa-fw me-2 text-danger"></i>Presensi Hari Ini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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

        .shadow-sm {
            box-shadow: 0 .125rem .25rem 0 rgba(58, 59, 69, .2) !important;
        }

        .card-footer {
            background-color: #f8f9fc;
            border-top: 1px solid #e3e6f0;
        }

        .card-footer a {
            color: #858796;
            text-decoration: none;
            transition: color .2s;
        }

        .card-footer a:hover {
            color: #4e73df;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartLabels = @json($chartLabels);
            const chartIPSValues = @json($chartIPSValues);
            const chartIPKValues = @json($chartIPKValues);

            if (chartLabels.length > 0) {
                const ctx = document.getElementById('ipkIpsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'IPS',
                            data: chartIPSValues,
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                        }, {
                            label: 'IPK',
                            data: chartIPKValues,
                            borderColor: '#6f42c1',
                            backgroundColor: 'rgba(111, 66, 193, 0.05)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            borderDash: [5, 5],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', align: 'end' },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.dataset.label + ': ' + context.raw.toFixed(2);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, max: 4.0, grid: { drawBorder: false } },
                            x: { grid: { display: false } }
                        },
                    }
                });
            }
        });
    </script>
@endpush