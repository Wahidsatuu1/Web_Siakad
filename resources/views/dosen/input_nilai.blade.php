@extends('layouts.app')

@section('title', 'Input Nilai Mahasiswa')
@section('header_title', 'Input Nilai Mahasiswa')

@section('content')
  <style>
    /* Form Container */
    .form-container {
    background: var(--white-bg);
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: var(--shadow-medium);
    margin-bottom: 2rem;
    }

    /* Form Header */
    .form-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.5rem;
    border-radius: 12px 12px 0 0;
    margin: -2.5rem -2.5rem 2rem -2.5rem;
    }

    .form-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    }

    /* Form Group */
    .form-group {
    margin-bottom: 1.75rem;
    position: relative;
    }

    .form-label {
    display: block;
    margin-bottom: 0.75rem;
    color: var(--text-color);
    font-weight: 500;
    font-size: 0.95rem;
    }

    /* Input Control */
    .form-control {
    width: 100%;
    padding: 0.875rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    transition: var(--transition);
    background-color: var(--white-bg);
    color: var(--text-color);
    }

    .form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    /* Input with Icon */
    .input-icon-container {
    position: relative;
    }

    .input-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    z-index: 2;
    }

    .input-icon-container .form-control {
    padding-left: 3rem;
    }

    /* Error Styles */
    .error-message {
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    }

    .error-message i {
    font-size: 1rem;
    }

    .is-invalid {
    border-color: var(--danger-color);
    }

    .is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(247, 37, 133, 0.15);
    }

    /* Error Notification */
    .error-notification {
    background-color: rgba(247, 37, 133, 0.1);
    color: var(--danger-color);
    padding: 1.25rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    border-left: 4px solid var(--danger-color);
    display: flex;
    gap: 0.75rem;
    }

    /* Section Divider */
    .section-divider {
    display: flex;
    align-items: center;
    margin: 2rem 0;
    color: var(--text-light);
    font-weight: 500;
    }

    .section-divider::before,
    .section-divider::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid var(--border-color);
    }

    .section-divider::before {
    margin-right: 1rem;
    }

    .section-divider::after {
    margin-left: 1rem;
    }

    /* Form Actions */
    .form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2.5rem;
    }

    /* Button Styles */
    .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.875rem 1.75rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    }

    .btn-primary {
    background-color: var(--primary-color);
    color: white;
    }

    .btn-primary:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }

    .btn-danger {
    background-color: var(--danger-color);

    }

    .btn-danger:hover {

    transform: translateY(-2px);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
    .form-container {
      padding: 2rem;
    }

    .form-header {
      margin: -2rem -2rem 1.5rem -2rem;
    }
    }

    @media (max-width: 768px) {
    .form-container {
      padding: 1.5rem;
    }

    .form-header {
      margin: -1.5rem -1.5rem 1.25rem -1.5rem;
      padding: 1.25rem;
    }

    .form-title {
      font-size: 1.3rem;
    }

    .form-actions {
      flex-direction: column;
    }

    .btn {
      width: 100%;
    }
    }

    @media (max-width: 576px) {
    .form-container {
      padding: 1.25rem;
    }

    .form-header {
      margin: -1.25rem -1.25rem 1rem -1.25rem;
      padding: 1rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }
    }
  </style>

  <div class="content-area">
    <div class="form-container">
    <div class="form-header">
      <h4 class="form-title">Form Input Nilai Mahasiswa</h4>
    </div>

    @if ($errors->any())
    <div class="error-notification">
      <i class="fas fa-exclamation-circle"></i>
      <div>
      <strong>Terjadi kesalahan:</strong>
      <ul style="margin: 0.5rem 0 0 1rem;">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
      </ul>
      </div>
    </div>
    @endif

    @if (session('error'))
    <div class="error-notification">
      <i class="fas fa-exclamation-circle"></i>
      <div>{{ session('error') }}</div>
    </div>
    @endif

    <form action="{{ route('dosen.kelolaNilaiMahasiswa.store') }}" method="POST">
      @csrf

      <div class="form-group">
      <label for="mahasiswa_id">Mahasiswa</label>
      <div class="input-icon-container">
        <i class="fas fa-user-graduate input-icon"></i>
        <select id="mahasiswa_id" name="mahasiswa_id" class="form-control @error('mahasiswa_id') is-invalid @enderror"
        required>
        <option value="">Pilih Mahasiswa</option>
        @foreach ($mahasiswas as $mhs)
      <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
        {{ $mhs->nim }} - {{ $mhs->nama }}
      </option>
      @endforeach
        </select>
      </div>
      @error('mahasiswa_id')
      <div class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <span>{{ $message }}</span>
      </div>
    @enderror
      </div>

      <div class="form-group">
      <label for="pengampu_mata_kuliah_id">Mata Kuliah & Kelas</label>
      <div class="input-icon-container">
        <i class="fas fa-book input-icon"></i>
        <select id="pengampu_mata_kuliah_id" name="pengampu_mata_kuliah_id"
        class="form-control @error('pengampu_mata_kuliah_id') is-invalid @enderror" required>
        <option value="">Pilih Mata Kuliah & Kelas</option>
        @foreach ($pengampuMataKuliahs as $pengampu)
      <option value="{{ $pengampu->id }}" {{ old('pengampu_mata_kuliah_id') == $pengampu->id ? 'selected' : '' }}>
        {{ $pengampu->mataKuliah->nama_mk }} ({{ $pengampu->kelas }})
      </option>
      @endforeach
        </select>
      </div>
      <small class="text-muted">Pilih mata kuliah dan kelas yang Anda ampu.</small>
      @error('pengampu_mata_kuliah_id')
      <div class="error-message">
      <i class="fas fa-exclamation-circle"></i>
      <span>{{ $message }}</span>
      </div>
    @enderror
      </div>

      <div class="section-divider">Komponen Nilai</div>

      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
        <label for="nilai_tugas">Nilai Tugas (0-100)</label>
        <div class="input-icon-container">
          <i class="fas fa-tasks input-icon"></i>
          <input type="number" step="0.01" id="nilai_tugas" name="nilai_tugas"
          class="form-control @error('nilai_tugas') is-invalid @enderror" value="{{ old('nilai_tugas') }}"
          placeholder="Contoh: 80" min="0" max="100">
        </div>
        @error('nilai_tugas')
      <div class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $message }}</span>
      </div>
      @enderror
        </div>
      </div>
      </div>

      <div class="row">
      <div class="col-md-6">
        <div class="form-group">
        <label for="nilai_uts">Nilai UTS (0-100)</label>
        <div class="input-icon-container">
          <i class="fas fa-file-alt input-icon"></i>
          <input type="number" step="0.01" id="nilai_uts" name="nilai_uts"
          class="form-control @error('nilai_uts') is-invalid @enderror" value="{{ old('nilai_uts') }}"
          placeholder="Contoh: 75" min="0" max="100">
        </div>
        @error('nilai_uts')
      <div class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $message }}</span>
      </div>
      @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
        <label for="nilai_uas">Nilai UAS (0-100)</label>
        <div class="input-icon-container">
          <i class="fas fa-scroll input-icon"></i>
          <input type="number" step="0.01" id="nilai_uas" name="nilai_uas"
          class="form-control @error('nilai_uas') is-invalid @enderror" value="{{ old('nilai_uas') }}"
          placeholder="Contoh: 88" min="0" max="100">
        </div>
        @error('nilai_uas')
      <div class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $message }}</span>
      </div>
      @enderror
        </div>
      </div>
      </div>

      <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan Nilai
      </button>
      <a href="{{ route('dosen.kelolaNilaiMahasiswa') }}" class="btn btn-danger">
        <i class="fas fa-times"></i> Batal
      </a>
      </div>
    </form>
    </div>
  </div>
@endsection