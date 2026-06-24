<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-calendar-check me-2 text-primary"></i>Detail Presensi</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12"><label for="mahasiswa_id" class="form-label required">Mahasiswa</label><select
              class="form-select" id="mahasiswa_id" name="mahasiswa_id" required>@foreach($mahasiswas as $mhs)<option
      value="{{ $mhs->id }}" {{ old('mahasiswa_id', $presensi->mahasiswa_id ?? '') == $mhs->id ? 'selected' : '' }}>{{ $mhs->nim }} - {{ $mhs->nama }}</option>@endforeach</select></div>
          <div class="col-12"><label for="pengampu_mata_kuliah_id" class="form-label required">Jadwal
              Kuliah</label><select class="form-select" id="pengampu_mata_kuliah_id" name="pengampu_mata_kuliah_id"
              required>@foreach($pengampuMataKuliahs as $pengampu)<option value="{{ $pengampu->id }}" {{ old('pengampu_mata_kuliah_id', $presensi->pengampu_mata_kuliah_id ?? '') == $pengampu->id ? 'selected' : '' }}>{{ $pengampu->mataKuliah->nama_mk }} - {{ $pengampu->dosen->nama }} ({{ $pengampu->hari }},
        {{ \Carbon\Carbon::parse($pengampu->jam_mulai)->format('H:i') }})
        </option>@endforeach</select></div>
          <div class="col-md-6"><label for="tanggal" class="form-label required">Tanggal</label><input type="date"
              class="form-control" id="tanggal" name="tanggal"
              value="{{ old('tanggal', $presensi->tanggal ? \Carbon\Carbon::parse($presensi->tanggal)->format('Y-m-d') : now()->format('Y-m-d')) }}"
              required></div>
          <div class="col-md-6">
            <label for="status_kehadiran" class="form-label required">Status</label>
            <select id="status_kehadiran" name="status_kehadiran" class="form-select" required>
              <option value="Hadir" {{ old('status_kehadiran', $presensi->status_kehadiran ?? '') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
              <option value="Tidak Hadir" {{ in_array(old('status_kehadiran', $presensi->status_kehadiran ?? ''), ['Tidak Hadir', 'Izin', 'Sakit', 'Alpha']) ? 'selected' : '' }}>Tidak Hadir</option>
            </select>
          </div>
          <div class="col-12"><label for="keterangan" class="form-label">Keterangan</label><textarea
              class="form-control" id="keterangan" name="keterangan"
              rows="3">{{ old('keterangan', $presensi->keterangan ?? '') }}</textarea></div>
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
        <p class="text-muted">Pastikan semua data terisi dengan benar. Data presensi akan mempengaruhi nilai akhir
          mahasiswa.</p>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" id="submit-button"><i
              class="fas fa-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.kelolaPresensi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
      </div>
    </div>
  </div>
</div>