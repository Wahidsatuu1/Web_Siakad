<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-calendar-plus me-2 text-primary"></i>Detail Jadwal Mengajar</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12"><label for="mata_kuliah_id" class="form-label required">Mata Kuliah</label><select
              class="form-select" id="mata_kuliah_id" name="mata_kuliah_id" required>@foreach($mataKuliahs as $mk)
          <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id ?? '') == $mk->id ? 'selected' : '' }}>{{ $mk->nama_mk }} ({{$mk->sks}} SKS) - {{ $mk->kelas }}</option>
        @endforeach</select></div>
          <div class="col-12"><label for="dosen_id" class="form-label required">Dosen Pengampu</label><select
              class="form-select" id="dosen_id" name="dosen_id" required>@foreach($dosens as $dosen)<option
      value="{{ $dosen->id }}" {{ old('dosen_id', $pengampuMataKuliah->dosen_id ?? '') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>@endforeach</select></div>
          <div class="col-md-6"><label for="hari" class="form-label required">Hari</label><select id="hari" name="hari"
              class="form-select" required>@foreach($haris as $hari)<option value="{{ $hari }}" {{ old('hari', $pengampuMataKuliah->hari ?? '') == $hari ? 'selected' : '' }}>{{ $hari }}</option>@endforeach</select>
          </div>
          <div class="col-md-6"><label for="ruangan" class="form-label required">Ruangan</label><input type="text"
              class="form-control" id="ruangan" name="ruangan"
              value="{{ old('ruangan', $pengampuMataKuliah->ruangan ?? '') }}" required></div>
          <div class="col-md-6"><label for="jam_mulai" class="form-label required">Jam Mulai</label><input type="time"
              class="form-control" id="jam_mulai" name="jam_mulai"
              value="{{ old('jam_mulai', $pengampuMataKuliah->jam_mulai ? \Carbon\Carbon::parse($pengampuMataKuliah->jam_mulai)->format('H:i') : '') }}"
              required></div>
          <div class="col-md-6"><label for="jam_selesai" class="form-label required">Jam Selesai</label><input
              type="time" class="form-control" id="jam_selesai" name="jam_selesai"
              value="{{ old('jam_selesai', $pengampuMataKuliah->jam_selesai ? \Carbon\Carbon::parse($pengampuMataKuliah->jam_selesai)->format('H:i') : '') }}"
              required></div>
          <div class="col-12"><label for="kelas" class="form-label required">Kelas</label><select id="kelas"
              name="kelas" class="form-select" required>
              <option value="">Pilih Kelas...</option>@foreach($kelasOptions as $option)<option value="{{ $option }}" {{ old('kelas', $pengampuMataKuliah->kelas ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
            </select></div>
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
        <p class="text-muted">Pastikan semua data terisi dengan benar. Jadwal tidak boleh tumpang tindih untuk ruangan
          yang sama.</p>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" id="submit-button"><i
              class="fas fa-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.pengampuMataKuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </div>
    </div>
  </div>
</div>