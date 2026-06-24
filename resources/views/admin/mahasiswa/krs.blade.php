@extends('layouts.mahasiswa') {{-- Sesuaikan dengan nama layout Anda --}}

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <h3 class="fw-bold mb-0">Input Kartu Rencana Studi (KRS)</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Tambah Mata Kuliah Mandiri</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mahasiswa.krs.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="small mb-1">Tahun Ajaran</label>
                        <input type="text" name="thn_ajaran" class="form-control" placeholder="Contoh: 2025/2026" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Kode MK</label>
                        <input type="text" name="kodemk" class="form-control" required>
                    </div>
                    <div class="col-md-1">
                        <label class="small mb-1">SKS</label>
                        <input type="number" name="sks" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Kelas</label>
                        <input type="text" name="kelas" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small mb-1">Kode Dosen</label>
                        <input type="text" name="kodedsn" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Ke KRS</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">KODE MK</th>
                            <th>TAHUN AJARAN</th>
                            <th>SMT</th>
                            <th>KELAS</th>
                            <th>SKS</th>
                            <th>DOSEN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($krsDetails as $krs)
                        <tr>
                            <td class="px-4 fw-bold text-dark">{{ $krs->kode_mk }}</td>
                            <td>{{ $krs->tahun_ajaran }}</td>
                            <td>{{ $krs->semester }}</td>
                            <td><span class="badge bg-info text-dark">{{ $krs->kelas }}</span></td>
                            <td>{{ $krs->sks }}</td>
                            <td>{{ $krs->kode_dsn }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="mb-3 opacity-25">
                                <p class="text-muted">Belum ada mata kuliah yang Anda input sendiri.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($krsDetails->count() > 0)
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="4" class="text-end px-4 py-3">TOTAL SKS</td>
                            <td colspan="2">{{ $totalSKS }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection