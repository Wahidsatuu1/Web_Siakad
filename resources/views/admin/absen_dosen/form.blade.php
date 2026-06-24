<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-calendar-check me-2 text-primary"></i>Detail Presensi</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12">
            <label for="dosen_id" class="form-label required">Dosen</label>
            {{-- Di halaman edit, nama dosen tidak bisa diubah --}}
            @if(isset($absenDosen) && $absenDosen->exists)
        <input type="hidden" name="dosen_id" value="{{ $absenDosen->dosen_id }}">
        <input type="text" class="form-control" value="{{ $absenDosen->dosen->nama }}" readonly>
      @else
          <select class="form-select" id="dosen_id" name="dosen_id" required>
            <option value="">Pilih Dosen...</option>
            @foreach($dosens as $dosen)
          <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}
          </option>
        @endforeach
          </select>
      @endif
          </div>
          <div class="col-md-6">
            <label for="tanggal" class="form-label required">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal"
              value="{{ old('tanggal', $absenDosen->tanggal ? \Carbon\Carbon::parse($absenDosen->tanggal)->format('Y-m-d') : now()->format('Y-m-d')) }}"
              required>
          </div>
          <div class="col-md-6">
            <label for="status" class="form-label required">Status Kehadiran</label>
            @php
        $currentStatus = old('status', $absenDosen->status ?? 'Hadir');
        $isHadir = ($currentStatus == 'Hadir');
        @endphp
            <select id="status" name="status" class="form-select" required>
              <option value="Hadir" {{ $isHadir ? 'selected' : '' }}>Hadir</option>
              <option value="Tidak Hadir" {{ !$isHadir ? 'selected' : '' }}>Tidak Hadir</option>
            </select>
          </div>
          <div class="col-md-6"><label for="waktu_masuk" class="form-label">Waktu Masuk</label><input type="time"
              class="form-control" id="waktu_masuk" name="waktu_masuk"
              value="{{ old('waktu_masuk', $absenDosen->waktu_masuk ? \Carbon\Carbon::parse($absenDosen->waktu_masuk)->format('H:i') : '') }}">
          </div>
          <div class="col-md-6"><label for="waktu_keluar" class="form-label">Waktu Keluar</label><input type="time"
              class="form-control" id="waktu_keluar" name="waktu_keluar"
              value="{{ old('waktu_keluar', $absenDosen->waktu_keluar ? \Carbon\Carbon::parse($absenDosen->waktu_keluar)->format('H:i') : '') }}">
          </div>
          <div class="col-12"><label for="keterangan" class="form-label">Keterangan</label><textarea
              class="form-control" id="keterangan" name="keterangan" rows="3"
              placeholder="Contoh: Rapat pimpinan, dinas luar kota, dll.">{{ old('keterangan', $absenDosen->keterangan ?? '') }}</textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-cog me-2 text-primary"></i>Aksi</h5>
      </div>
      <div class="card-body">
        <p class="text-muted">Jika status "Tidak Hadir", waktu masuk dan keluar akan diabaikan.</p>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" id="submit-button"><i
              class="fas fa-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.absenDosens.index') }}" class="btn btn-secondary">Batal</a>
        </div>
      </div>
    </div>
  </div>
</div>