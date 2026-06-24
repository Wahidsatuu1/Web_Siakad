@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')
@section('header_title', 'Daftar Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white p-3">
            <h4 class="mb-3 fw-bold text-gradient">
                <i class="fas fa-users me-2"></i>Daftar Mahasiswa Bimbingan
            </h4>
            <hr>
            {{-- Filter Form diubah menjadi kontrol DataTables --}}
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <label class="input-group-text" for="kelas_filter">Kelas</label>
                        <select id="kelas_filter" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasDiajarOlehDosen as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                     <div class="input-group input-group-sm">
                        <label class="input-group-text" for="matakuliah_filter">Mata Kuliah</label>
                        <select id="matakuliah_filter" class="form-select">
                            <option value="">Semua Mata Kuliah</option>
                             @foreach($mataKuliahsDiajar as $pengampu)
                                <option value="{{ optional($pengampu->mataKuliah)->nama_mk }}">
                                    {{ optional($pengampu->mataKuliah)->nama_mk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                 <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="custom-search-input" class="form-control border-start-0" placeholder="Cari nama/NIM...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 @if($mahasiswas->isEmpty()) is-empty @endif"
                    id="mahasiswa-dosen-table" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">No.</th>
                            <th class="text-center">Mahasiswa</th>
                            <th class="text-center">NIM</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Presensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswas as $mhs)
                        <tr id="mahasiswa-row-{{ $mhs->id }}">
                            <td class="text-center"></td>
                            <td data-label="Mahasiswa">{{ $mhs->nama }}</td>
                            <td data-label="NIM" class="text-start">{{ $mhs->nim }}</td>
                            <td data-label="Kelas" class="text-start">{{ $mhs->kelas }}</td>
                            <td data-label="Presensi" class="text-center">
                                @php
                                    $isHadirToday = isset($presensiStatusHariIni[$mhs->id]);
                                @endphp
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input presensi-toggle" type="checkbox" role="switch"
                                        id="presensiToggle{{ $mhs->id }}" 
                                        data-mahasiswa-id="{{ $mhs->id }}" 
                                        {{ $isHadirToday ? 'checked' : '' }}>
                                    <label class="form-check-label" for="presensiToggle{{ $mhs->id }}">
                                        <span id="presensi-status-text-{{ $mhs->id }}" 
                                            class="badge rounded-pill {{ $isHadirToday ? 'bg-success' : 'bg-danger' }} ms-2">
                                            {{ $isHadirToday ? 'Hadir' : 'Tidak Hadir' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p class="mb-0">Tidak ada mahasiswa yang terdaftar di kelas yang Anda ajar.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Style yang konsisten dengan halaman lain */
    .text-gradient { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; background-clip: text; color: transparent; }
    .table th { font-weight: 600; font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; }
    .table td { vertical-align: middle; font-size: .875rem; }
    .form-switch .form-check-input { width: 3em; height: 1.5em; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    var table = $('#mahasiswa-dosen-table').DataTable({
        dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
        paging: false,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: false,
        order: [[1, 'asc']], // Urutkan berdasarkan nama mahasiswa
        columnDefs: [
            { searchable: false, orderable: false, targets: [0, 4] },
            { targets: [1, 2, 3, 4], className: 'text-nowrap' } // Mencegah wrap teks
        ],
        "fnDrawCallback": function (oSettings) {
            this.api().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });

    // Custom search
    $('#custom-search-input').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

    // Custom filter for Kelas
    $('#kelas_filter').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
    });
    
    document.querySelectorAll('.presensi-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const mahasiswaId = this.dataset.mahasiswaId;
            const isPresent = this.checked;
            const statusTextSpan = document.getElementById(`presensi-status-text-${mahasiswaId}`);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // ... sisa script fetch AJAX Anda ...
            fetch("{{ route('dosen.togglePresensiHarian') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    mahasiswa_id: mahasiswaId,
                    is_present: isPresent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusTextSpan.textContent = data.status_kehadiran;
                    statusTextSpan.classList.toggle('bg-success', data.status_kehadiran === 'Hadir');
                    statusTextSpan.classList.toggle('bg-danger', data.status_kehadiran !== 'Hadir');
                } else {
                    this.checked = !isPresent; // Revert
                    alert(data.message || 'Gagal memperbarui status.');
                }
            }).catch(error => {
                this.checked = !isPresent; // Revert
                alert('Terjadi kesalahan koneksi.');
            });
        });
    });
});
</script>
@endpush