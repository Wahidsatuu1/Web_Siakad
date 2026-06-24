@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS)')
@section('header_title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="container-fluid">
    {{-- 1. Form Tambah Mata Kuliah Mandiri --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white p-3">  
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Tambah Mata Kuliah Mandiri</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('mahasiswa.krs.store') }}" method="POST">
                @csrf
                {{-- Tambahkan input hidden untuk tahun ajaran jika tidak ada di form --}}
                <input type="hidden" name="thn_ajaran" value="2025/2026"> 
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Pilih Mata Kuliah</label>
                        <select name="kodemk" class="form-select" id="select_mk" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($mataKuliahDasar as $mk)
                                <option value="{{ $mk->kode_mk }}" 
                                        data-nama="{{ $mk->nama_mk }}" 
                                        data-sks="{{ $mk->sks }}">
                                    {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Nama Mata Kuliah</label>
                        <input type="text" name="namamk" id="nama_mk" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">SKS</label>
                        <input type="number" name="sks" id="sks_mk" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Kode Dosen</label>
                        <input type="text" name="kodedsn" class="form-control" placeholder="ID Dosen" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. Daftar KRS --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0 fw-bold text-gradient">
                    <i class="fas fa-tasks me-2"></i>Daftar KRS Anda
                </h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('mahasiswa.krs.exportXls') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i> Export XLS
                    </a>
                    
                    <a href="{{ route('mahasiswa.krs.exportPdf') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 @if($krsDetails->isEmpty()) is-empty @endif" id="krs-table" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">No.</th>
                            <th>Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen Pengampu</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($krsDetails as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td data-label="Kode MK">{{ $item->kode_mk }}</td>
                            <td data-label="Mata Kuliah">
                                <span class="fw-semibold">
                                    {{ $item->mataKuliah->nama_mk ?? 'Nama Tidak Ditemukan' }}
                                </span>
                            </td>
                            <td data-label="Dosen">{{ $item->kode_dsn }}</td>
                            <td data-label="Kelas" class="text-center">{{ $item->kelas ?? '-' }}</td>
                            <td data-label="SKS" class="text-center">
                                <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-2">
                                    {{ $item->sks }} SKS
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('mahasiswa.krs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p class="mb-0">Belum ada mata kuliah yang direncanakan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(!$krsDetails->isEmpty())
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end pe-4">Total SKS</td>
                            <td class="text-center">{{ $totalSKS }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-gradient {
        background: linear-gradient(135deg, #4e73df, #224abe);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .table th { font-size: .8rem; text-transform: uppercase; }
    @media print {
        .card-header .d-flex, .btn, form, .text-danger { display: none !important; }
        body { background: white; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Script untuk isi otomatis Nama MK dan SKS
        $('#select_mk').on('change', function() {
            let selected = $(this).find(':selected');
            $('#nama_mk').val(selected.data('nama'));
            $('#sks_mk').val(selected.data('sks'));
        });

        // DataTable Initialization
        var table = $('#krs-table:not(.is-empty)').DataTable({
            dom: 'rtip',
            paging: false,
            info: false,
            searching: true
        });

        $('#custom-search-input').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>
@endpush