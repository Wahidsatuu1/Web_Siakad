@extends('layouts.app')

@section('title', 'Presensi Mahasiswa')
@section('header_title', 'Presensi Kuliah')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h4 class="mb-3 fw-bold text-gradient">
          <i class="fas fa-user-check me-2"></i>Presensi Kuliah Hari Ini ({{ $hariIni }})
        </h4>
        <hr>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="input-group input-group-sm" style="max-width: 350px;">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="custom-search-input" class="form-control border-start-0"
              placeholder="Cari mata kuliah, dosen...">
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 @if($jadwalHariIni->isEmpty()) is-empty @endif"
            id="presensi-mahasiswa-table" style="width:100%">
            <thead class="table-light">
              <tr>
                <th>Mata Kuliah</th>
                <th>Jam Kuliah</th>
                <th>Dosen</th>
                <th class="text-center">Ruangan</th>
                <th class="text-center" style="width: 220px;">Presensi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($jadwalHariIni as $jadwal)
            <tr id="jadwal-row-mahasiswa-{{ $jadwal->id }}">
            <td data-label="Mata Kuliah">
              <span class="fw-semibold">{{ $jadwal->mataKuliah->nama_mk ?? 'N/A' }}</span>
              <small class="d-block text-muted">{{ $jadwal->mataKuliah->kode_mk ?? 'N/A' }}</small>
            </td>
            <td data-label="Jam Kuliah">
              {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
              {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
            </td>
            <td data-label="Dosen">{{ $jadwal->dosen->nama ?? 'N/A' }}</td>
            <td data-label="Ruangan" class="text-center">
              <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $jadwal->ruangan }}</span>
            </td>
            <td data-label="Presensi Anda" class="text-center">
              @php
          $presensiDetail = $presensiSudahDilakukan->get($jadwal->id);
          $statusPresensi = $presensiDetail ? $presensiDetail->status_kehadiran : null;
          $isHadir = $statusPresensi === 'Hadir';
          @endphp
              <div class="presensi-controls-mahasiswa" data-jadwal-id="{{ $jadwal->id }}">
              <div class="form-check form-switch">
                <input class="form-check-input presensi-mahasiswa-toggle" 
                type="checkbox" 
                role="switch"
                id="presensiToggleMahasiswa{{ $jadwal->id }}" 
                data-pengampu-id="{{ $jadwal->id }}"
                {{ $isHadir ? 'checked' : '' }}>
              </div>
              <span id="presensi-mahasiswa-status-text-{{ $jadwal->id }}"
                class="badge rounded-pill {{ $isHadir ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
                {{ $statusPresensi ?? 'Belum Presensi' }}
              </span>
              </div>
            </td>
            </tr>
        @empty
          <tr>
          <td colspan="5" class="text-center py-5 text-muted">
            <i class="fas fa-calendar-check fa-3x mb-3"></i>
            <p class="mb-0">Tidak ada jadwal kuliah hari ini.</p>
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
    .presensi-controls-mahasiswa { display: flex; align-items: center; gap: 0.75rem; justify-content: center; }
    .form-switch .form-check-input { width: 3em; height: 1.5em; cursor: pointer; }

    @media (max-width: 991.98px) {
      .table thead { display: none; }
      .table tr { display: block; margin-bottom: 1rem; border: 1px solid #dee2e6; border-radius: .5rem; }
      .table td { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding: .75rem 1rem; }
      .table td::before { content: attr(data-label); font-weight: 600; color: #6c757d; margin-right: 1rem; }
      .table td:last-child { border-bottom: 0; }
    }
  </style>
@endpush

@push('scripts')
  {{-- Menambahkan script DataTables dan mempertahankan script presensi Anda --}}
  <script>
  $(document).ready(function () {
    // Inisialisasi DataTables
    var table = $('#presensi-mahasiswa-table:not(.is-empty)').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: false, // Paging dinonaktifkan untuk daftar harian
      lengthChange: false,
      searching: true,
      ordering: true,
      info: false, // Info dinonaktifkan
      order: [[1, 'asc']], // Urutkan berdasarkan Jam Kuliah
      language: {
        search: "",
        zeroRecords: "Data tidak ditemukan.",
      },
      columnDefs: [
        // Kolom Presensi tidak perlu diurutkan
        { orderable: false, targets: 4 },
      ],
    });

    $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
    });

    // --- SCRIPT PRESENSI AJAX ANDA YANG SUDAH ADA ---
    // Kode ini tetap sama karena fungsionalitasnya tidak berubah
    // Saya hanya memindahkannya ke dalam $(document).ready() untuk kerapian
    const globalAlertContainer = document.getElementById('global-alert-container-mahasiswa');

    function showAlert(type, message) { /* ... fungsi showAlert Anda ... */ }

    document.querySelectorAll('.presensi-mahasiswa-toggle').forEach(toggle => {
      toggle.addEventListener('change', function () {
        const pengampuId = this.dataset.pengampuId;
        const isChecked = this.checked;
        const statusTextSpan = document.getElementById(`presensi-mahasiswa-status-text-${pengampuId}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!statusTextSpan || !csrfToken) {
          console.error('Elemen penting tidak ditemukan atau CSRF token hilang.');
          return;
        }

        const statusYangDikirim = isChecked ? 'Hadir' : 'Tidak Hadir';

        // Update UI sementara
        statusTextSpan.textContent = 'Memproses...';
        this.disabled = true;

        fetch('{{ route("mahasiswa.presensi.submit") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            pengampu_mata_kuliah_id: pengampuId,
            status_kehadiran: statusYangDikirim,
          })
        })
        .then(response => response.json())
        .then(data => {
          this.disabled = false;
          if (data.success) {
            statusTextSpan.textContent = data.status_kehadiran_display;
            statusTextSpan.className = `badge rounded-pill ${data.status_kehadiran_display === 'Hadir' ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis'}`;
          } else {
            // Revert jika gagal
            this.checked = !isChecked;
            statusTextSpan.textContent = isChecked ? 'Belum Presensi' : 'Hadir';
             statusTextSpan.className = `badge rounded-pill ${!isChecked ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis'}`;
            alert(data.message || 'Gagal memperbarui presensi.');
          }
        })
        .catch(error => {
          this.disabled = false;
          this.checked = !isChecked;
          statusTextSpan.textContent = isChecked ? 'Belum Presensi' : 'Hadir';
          statusTextSpan.className = `badge rounded-pill ${!isChecked ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis'}`;
          console.error('Error:', error);
          alert('Terjadi kesalahan koneksi.');
        });
      });
    });
  });
  </script>
@endpush