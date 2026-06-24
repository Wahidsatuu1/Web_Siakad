@extends('layouts.app')

@section('title', 'Absensi Dosen')
@section('header_title', 'Absensi Harian')

@section('content')
  <style>
    /* Notification Styles */
    .alert-notification {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    box-shadow: var(--shadow-light);
    border-left: 4px solid;
    }

    .alert-success {
    background-color: rgba(76, 201, 240, 0.1);
    color: #155724;
    border-left-color: var(--success-color);
    }

    .alert-danger {
    background-color: rgba(247, 37, 133, 0.1);
    color: #721c24;
    border-left-color: var(--danger-color);
    }

    /* Card Styles */
    .attendance-card {
    border: none;
    border-radius: 16px;
    box-shadow: var(--shadow-medium);
    overflow: hidden;
    margin-bottom: 2rem;
    background: var(--white-bg);
    transition: var(--transition);
    }

    .attendance-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.25rem 1.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    }

    .card-riwayat {

    color: gray;
    padding: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
    }

    .card-body {
    padding: 1.5rem;
    }

    /* Status Card */
    .status-card {
    background: var(--white-bg);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-light);
    }

    .status-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px dashed var(--border-color);
    }

    .status-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
    }

    .status-label {
    color: var(--text-light);
    font-weight: 500;
    }

    .status-value {
    font-weight: 600;
    color: var(--text-color);
    }

    /* Button Styles */
    .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    box-shadow: var(--shadow-light);
    }

    .btn-checkin {
    background-color: var(--success-color);
    color: white;
    }

    .btn-checkin:hover {
    transform: translateY(-2px);
    }

    .btn-checkout {
    background-color: var(--success-color);
    color: white;
    }

    .btn-checkout:hover {
    transform: translateY(-2px);
    }

    /* Table Styles */
    .attendance-table-container {
    overflow-x: auto;
    border-radius: 12px;
    background: var(--white-bg);
    box-shadow: var(--shadow-light);
    }

    .attendance-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 600px;
    }

    .attendance-table thead tr {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    }

    .attendance-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 500;
    position: sticky;
    top: 0;
    }

    .attendance-table td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
    }

    .attendance-table tr:last-child td {
    border-bottom: none;
    }

    .attendance-table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    }

    /* Status Badges */
    .status-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    }

    .status-hadir {
    background-color: rgba(76, 201, 240, 0.1);
    color: var(--success-color);
    }

    .status-terlambat {
    background-color: rgba(248, 150, 30, 0.1);
    color: var(--warning-color);
    }

    .status-tanpa-keterangan {
    background-color: rgba(247, 37, 133, 0.1);
    color: var(--danger-color);
    }

    /* Empty State */
    .empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-light);
    }

    .empty-state-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--text-light);
    opacity: 0.7;
    }

    .empty-state-text {
    font-size: 1rem;
    margin-bottom: 1.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
    .card-body {
      padding: 1.25rem;
    }

    .status-card {
      padding: 1.25rem;
    }
    }

    @media (max-width: 768px) {

    .attendance-table th,
    .attendance-table td {
      padding: 0.75rem;
      font-size: 0.875rem;
    }

    .btn-action {
      padding: 0.75rem 1.25rem;
      font-size: 0.875rem;
    }
    }

    @media (max-width: 576px) {
    .card-header {
      padding: 1rem;
      font-size: 1rem;
    }

    .status-item {
      flex-direction: column;
      gap: 0.25rem;
    }

    .empty-state {
      padding: 1.5rem 1rem;
    }

    .empty-state-icon {
      font-size: 2rem;
    }
    }
  </style>

  <div class="content-area">
    @if (session('success'))
    <div class="alert-notification alert-success">
    <i class="fas fa-check-circle"></i>
    <div>{{ session('success') }}</div>
    </div>
    @endif

    @if (session('error'))
    <div class="alert-notification alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    <div>{{ session('error') }}</div>
    </div>
    @endif

    <div class="attendance-card">
    <div class="card-header">
      Status Absensi Hari Ini ({{ \Carbon\Carbon::today()->format('d F Y') }})
    </div>
    <div class="card-body">
      @if ($absenToday)
      <div class="status-card">
      <div class="status-item">
      <span class="status-label">Status:</span>
      <span class="status-value">{{ $absenToday->status }}</span>
      </div>
      <div class="status-item">
      <span class="status-label">Waktu Masuk:</span>
      <span class="status-value">{{ $absenToday->waktu_masuk ?? '-' }}</span>
      </div>
      <div class="status-item">
      <span class="status-label">Waktu Keluar:</span>
      <span class="status-value">{{ $absenToday->waktu_keluar ?? '-' }}</span>
      </div>
      </div>

      @if (!$absenToday->waktu_keluar)
      <form action="{{ route('dosen.absen.checkOut') }}" method="POST">
      @csrf
      <button type="submit" class="btn-action btn-checkout">
      <i class="fas fa-sign-out-alt"></i> Absen Keluar
      </button>
      </form>
    @else
      <div class="text-center text-success mt-3">
      <i class="fas fa-check-circle"></i> Anda sudah melakukan absen masuk & keluar hari ini.
      </div>
    @endif
    @else
      <div class="text-center mb-3">
      <p>Anda belum melakukan absen masuk hari ini.</p>
      </div>
      <form action="{{ route('dosen.absen.checkIn') }}" method="POST">
      @csrf
      <button type="submit" class="btn-action btn-checkin">
      <i class="fas fa-sign-in-alt"></i> Absen Masuk
      </button>
      </form>
    @endif
    </div>
    </div>

    <div class="card-riwayat">
    Riwayat Absensi (7 Hari Terakhir)
    </div>
  </div>
  <div class="attendance-card">
    <div class="attendance-table-container">
    <table class="attendance-table">
      <thead>
      <tr>
        <th>Tanggal</th>
        <th>Waktu Masuk</th>
        <th>Waktu Keluar</th>
        <th>Status</th>
        <th>Keterangan</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($riwayatAbsen as $absen)
      <tr>
        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
        <td>{{ $absen->waktu_masuk ?? '-' }}</td>
        <td>{{ $absen->waktu_keluar ?? '-' }}</td>
        <td>
        <span class="status-badge 
      @if($absen->status == 'Hadir') status-hadir
      @elseif($absen->status == 'Terlambat') status-terlambat
      @else status-tanpa-keterangan @endif">
        {{ $absen->status }}
        </span>
        </td>
        <td>{{ $absen->keterangan ?? '-' }}</td>
      </tr>
    @empty
      <tr>
      <td colspan="5" class="empty-state">
      <i class="fas fa-history empty-state-icon"></i>
      <p class="empty-state-text">Tidak ada riwayat absen.</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>

@endsection