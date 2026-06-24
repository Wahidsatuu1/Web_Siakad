<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-user-graduate me-2 text-primary"></i>Informasi Mahasiswa & Mata
          Kuliah</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6"><label for="mahasiswa_id" class="form-label required">Mahasiswa</label><select
              class="form-select" id="mahasiswa_id" name="mahasiswa_id" required>@foreach($mahasiswas as $mhs)<option
      value="{{ $mhs->id }}" {{ old('mahasiswa_id', $nilaiMahasiswa->mahasiswa_id ?? '') == $mhs->id ? 'selected' : '' }}>{{ $mhs->nim }} - {{ $mhs->nama }}</option>@endforeach</select></div>
          <div class="col-md-6"><label for="kelas" class="form-label required">Kelas</label><select id="kelas"
              name="kelas" class="form-select" required>@foreach($kelasOptions as $option)<option value="{{ $option }}"
        {{ old('kelas', $nilaiMahasiswa->kelas ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach</select></div>
          <div class="col-12"><label for="mata_kuliah_id" class="form-label required">Mata Kuliah</label><select
              class="form-select" id="mata_kuliah_id" name="mata_kuliah_id" required>@foreach($mataKuliahs as $mk)
        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $nilaiMahasiswa->mata_kuliah_id ?? '') == $mk->id ? 'selected' : '' }}>{{ $mk->nama_mk }}</option>@endforeach</select></div>
          <div class="col-12"><label for="dosen_id" class="form-label">Dosen Pemberi Nilai</label><select
              class="form-select" id="dosen_id" name="dosen_id">@foreach($dosens as $dosen)<option
      value="{{ $dosen->id }}" {{ old('dosen_id', $nilaiMahasiswa->dosen_id ?? '') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>@endforeach</select><small class="text-muted">Akan terisi otomatis
              berdasarkan jadwal mengajar jika tersedia.</small></div>
        </div>
      </div>
    </div>
    <div class="card shadow-sm border-0 mt-4">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-clipboard-check me-2 text-primary"></i>Komponen Nilai</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4"><label for="nilai_tugas" class="form-label">Tugas</label><input type="number"
              step="0.01" id="nilai_tugas" name="nilai_tugas" class="form-control"
              value="{{ old('nilai_tugas', $nilaiMahasiswa->nilai_tugas ?? '0') }}" min="0" max="100"></div>
          <div class="col-md-4"><label for="nilai_uts" class="form-label">UTS</label><input type="number" step="0.01"
              id="nilai_uts" name="nilai_uts" class="form-control"
              value="{{ old('nilai_uts', $nilaiMahasiswa->nilai_uts ?? '0') }}" min="0" max="100"></div>
          <div class="col-md-4"><label for="nilai_uas" class="form-label">UAS</label><input type="number" step="0.01"
              id="nilai_uas" name="nilai_uas" class="form-control"
              value="{{ old('nilai_uas', $nilaiMahasiswa->nilai_uas ?? '0') }}" min="0" max="100"></div>
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
        <p class="text-muted">Nilai akhir dan grade akan dihitung secara otomatis oleh sistem.</p>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" id="submit-button"><i
              class="fas fa-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.nilaiMahasiswas.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
      </div>
    </div>
  </div>
</div>