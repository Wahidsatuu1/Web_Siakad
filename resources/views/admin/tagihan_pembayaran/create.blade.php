@extends('layouts.app')

@section('title', 'Buat Tagihan Pembayaran - Admin')
@section('header_title', 'Buat Tagihan Pembayaran')

@section('content')
    <style>
        /* Container Form */
        .form-container {
            background: var(--white-bg);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
        }

        /* Judul Form */
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-title {
            margin: 0;
            font-size: 1.25rem;
        }

        /* Notifikasi Error */
        .alert-error {
            background-color: #fce8e8;
            color: #cc0000;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc3545;
        }

        /* Grup Form */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        /* Input Control */
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        /* Select Custom */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        /* Grup dengan Ikon */
        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .input-group .form-control {
            padding-left: 2.5rem;
        }

        /* Input dengan Prefix */
        .input-prefix {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-color);
            font-weight: 500;
        }

        .input-group-prefix .form-control {
            padding-left: 3rem;
        }

        /* Tombol Aksi Form */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Responsif untuk Tablet/Mobile */
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <div class="content-area">
        <div class="form-container">
            <div class="card-header">
                <h4 class="card-title">Form Buat Tagihan Pembayaran</h4>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 0.5rem 0 0 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.tagihanPembayarans.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="mahasiswa_id">Mahasiswa</label>
                    <div class="input-group">
                        <i class="fas fa-user-graduate input-icon"></i>
                        <select id="mahasiswa_id" name="mahasiswa_id" class="form-control" required>
                            <option value="">Pilih Mahasiswa</option>
                            @foreach ($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nim }} - {{ $mhs->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="jenis_tagihan">Jenis Tagihan</label>
                    <div class="input-group">
                        <i class="fas fa-tags input-icon"></i>
                        <select id="jenis_tagihan" name="jenis_tagihan" class="form-control" required>
                            <option value="">Pilih Jenis Tagihan</option>
                            @foreach ($jenisTagihanOptions as $type)
                                <option value="{{ $type }}" {{ old('jenis_tagihan') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="jumlah_tagihan">Jumlah Tagihan</label>
                    <div class="input-group input-group-prefix">
                        <span class="input-prefix">Rp</span>
                        <input type="number" step="0.01" id="jumlah_tagihan" name="jumlah_tagihan"
                            value="{{ old('jumlah_tagihan') }}" placeholder="2500000.00" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="semester">Semester (Opsional)</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <select id="semester" name="semester" class="form-control">
                            <option value="">Pilih Semester (Jika Berlaku)</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester }}" {{ old('semester') == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tanggal_batas_pembayaran">Tanggal Batas Pembayaran</label>
                    <div class="input-group">
                        <i class="fas fa-calendar-day input-icon"></i>
                        <input type="date" id="tanggal_batas_pembayaran" name="tanggal_batas_pembayaran"
                            value="{{ old('tanggal_batas_pembayaran') }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status_pembayaran">Status Pembayaran</label>
                    <div class="input-group">
                        <i class="fas fa-info-circle input-icon"></i>
                        <select id="status_pembayaran" name="status_pembayaran" class="form-control" required>
                            @foreach ($statusPembayaranOptions as $status)
                                <option value="{{ $status }}" {{ old('status_pembayaran') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="form-control"
                        placeholder="Tambahkan keterangan tambahan jika diperlukan.">{{ old('keterangan') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Tagihan
                    </button>
                    <a href="{{ route('admin.tagihanPembayarans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection