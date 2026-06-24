@extends('layouts.app')

@section('title', 'Kelola Tagihan Pembayaran - Admin')
@section('header_title', 'Kelola Tagihan Pembayaran')

@section('content')
  <style>
    /* Notifikasi Sukses */
    .alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #28a745;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    }

    /* Header Halaman */
    .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
    }

    .page-title {
    margin: 0;
    color: var(--text-color);
    font-size: 1.5rem;
    font-weight: 600;
    }

    /* Tombol */
    .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    }

    .btn-primary {
    background-color: var(--primary-color);
    color: white;
    }

    .btn-primary:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);
    }


    .btn-warning:hover {
    transform: translateY(-2px);
    }

    .btn-danger:hover {
    transform: translateY(-2px);
    }

    /* Tabel Data */
    .data-table-container {
    overflow-x: auto;
    background: var(--white-bg);
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    margin-bottom: 2rem;
    }

    .data-table {
    width: 100%;
    border-collapse: collapse;
    }

    .data-table thead {
    background-color: var(--primary-color);
    color: white;
    }

    .data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 500;
    }

    .data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    }

    .data-table tr:last-child td {
    border-bottom: none;
    }

    .data-table tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    }

    /* Status Tagihan */
    .status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
    }

    .status-lunas {

    background-color: var(--bs-success);
    color: white;
    }

    .status-belum-lunas {
    background-color: var(--bs-danger);
    color: white;
    }

    .status-menunggu {
    background-color: var(--bs-info);
    color: white;
    }

    /* Tombol Aksi */
    .action-buttons {
    display: flex;
    gap: 0.5rem;
    }

    /* Tampilan Kosong */
    .empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-light);
    }

    .empty-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-light);
    }

    /* Responsif untuk Tablet */
    @media (max-width: 992px) {

    .data-table td:nth-child(1),
    .data-table th:nth-child(1) {
      display: none;
    }
    }

    /* Responsif untuk Mobile */
    @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .action-buttons {
      flex-direction: column;
      gap: 0.5rem;
    }

    .btn {
      width: 100%;
      justify-content: center;
    }
    }

    @media (max-width: 576px) {

    .data-table th,
    .data-table td {
      padding: 0.75rem;
    }

    /* Sembunyikan teks tombol di mobile */
    .btn-text {
      display: none;
    }

    /* Tombol ikon saja di mobile */
    .btn i {
      margin: 0;
    }
    }
  </style>

  <div class="content-area">
    @if (session('success'))
    <div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
    </div>
    @endif

    <div class="page-header">
    <h3 class="page-title">Daftar Tagihan Pembayaran</h3>
    <a href="{{ route('admin.tagihanPembayarans.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Buat Tagihan Baru</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Mahasiswa</th>
        <th>NIM</th>
        <th>Jenis Tagihan</th>
        <th>Jumlah</th>
        <th>Batas Pembayaran</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($tagihanPembayarans as $tagihan)
      <tr>
        <td>{{ $tagihan->id }}</td>
        <td>{{ $tagihan->mahasiswa->nama }}</td>
        <td>{{ $tagihan->mahasiswa->nim }}</td>
        <td>{{ $tagihan->jenis_tagihan }}</td>
        <td>Rp{{ number_format($tagihan->jumlah_tagihan, 2, ',', '.') }}</td>
        <td>{{ $tagihan->tanggal_batas_pembayaran->format('d/m/Y') }}</td>
        <td>
        <span class="status-badge 
      @if($tagihan->status_pembayaran == 'Lunas') status-lunas
      @elseif($tagihan->status_pembayaran == 'Belum Lunas') status-belum-lunas
      @else status-menunggu @endif">
        {{ $tagihan->status_pembayaran }}
        </span>
        </td>
        <td>
        <div class="action-buttons">
        <a href="{{ route('admin.tagihanPembayarans.edit', $tagihan->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.tagihanPembayarans.destroy', $tagihan->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
          onclick="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?')">
          <i class="fas fa-trash-alt"></i>
          <span class="btn-text">Hapus</span>
        </button>
        </form>
        </div>
        </td>
      </tr>
    @empty
      <tr>
      <td colspan="8" class="empty-state">
      <i class="fas fa-file-invoice-dollar"></i>
      <p>Tidak ada data tagihan pembayaran</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection