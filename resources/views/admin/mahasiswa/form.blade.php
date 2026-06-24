<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-id-card me-2 text-primary"></i>Profil Mahasiswa</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6"><label for="nim" class="form-label required">NIM</label><input type="text"
              class="form-control" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required></div>
          <div class="col-md-6"><label for="nama" class="form-label required">Nama Lengkap</label><input type="text"
              class="form-control" id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama ?? '') }}" required>
          </div>
          <div class="col-md-6"><label for="jurusan" class="form-label required">Jurusan</label><input type="text"
              class="form-control" id="jurusan" name="jurusan" value="{{ old('jurusan', $mahasiswa->jurusan ?? '') }}"
              required></div>
          <div class="col-md-6"><label for="angkatan" class="form-label required">Angkatan</label><input type="number"
              class="form-control" id="angkatan" name="angkatan"
              value="{{ old('angkatan', $mahasiswa->angkatan ?? '') }}" placeholder="Contoh: 2023" required></div>
          <div class="col-md-6"><label for="program_studi" class="form-label">Program Studi</label><select
              id="program_studi" name="program_studi" class="form-select"> @foreach ($programStudiOptions as $option)
        <option value="{{ $option }}" {{ old('program_studi', $mahasiswa->program_studi ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option> @endforeach
            </select></div>
          <div class="col-md-6"><label for="prog_perkuliahan" class="form-label">Program Perkuliahan</label><select
              id="prog_perkuliahan" name="prog_perkuliahan" class="form-select">
              @foreach ($progPerkuliahanOptions as $option) <option value="{{ $option }}" {{ old('prog_perkuliahan', $mahasiswa->prog_perkuliahan ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option> @endforeach
            </select></div>
          <div class="col-md-6"><label for="kelas" class="form-label">Kelas</label><select id="kelas" name="kelas"
              class="form-select"> @foreach ($kelasOptions as $option) <option value="{{ $option }}" {{ old('kelas', $mahasiswa->kelas ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option> @endforeach </select>
          </div>
          <div class="col-md-6"><label for="status_mahasiswa" class="form-label required">Status</label><select
              id="status_mahasiswa" name="status_mahasiswa" class="form-select" required>
              @foreach ($statusMahasiswaOptions as $option) <option value="{{ $option }}" {{ old('status_mahasiswa', $mahasiswa->status_mahasiswa ?? 'Aktif') == $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach </select></div>
          <div class="col-md-6"><label for="tanggal_lahir" class="form-label">Tanggal Lahir</label><input type="date"
              class="form-control" id="tanggal_lahir" name="tanggal_lahir"
              value="{{ old('tanggal_lahir', optional($mahasiswa->tanggal_lahir ?? null)->format('Y-m-d')) }}"></div>
          <div class="col-md-6"><label for="telepon" class="form-label">Telepon</label><input type="tel"
              class="form-control" id="telepon" name="telepon" value="{{ old('telepon', $mahasiswa->telepon ?? '') }}">
          </div>
          <div class="col-12"><label for="alamat" class="form-label">Alamat</label><textarea class="form-control"
              id="alamat" name="alamat" rows="2">{{ old('alamat', $mahasiswa->alamat ?? '') }}</textarea></div>
        </div>
      </div>
    </div>
    <div class="card shadow-sm border-0 mt-4">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-user-lock me-2 text-primary"></i>Akun Login</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12"><label for="email" class="form-label required">Email</label><input type="email"
              class="form-control" id="email" name="email" value="{{ old('email', $mahasiswa->user->email ?? '') }}"
              required></div>
          <div class="col-md-6"><label for="password"
              class="form-label {{ isset($mahasiswa) ? '' : 'required' }}">Password</label><input type="password"
              class="form-control" id="password" name="password" {{ isset($mahasiswa) ? '' : 'required' }}><small
              class="text-muted">{{ isset($mahasiswa) ? 'Kosongkan jika tidak diubah.' : '' }}</small></div>
          <div class="col-md-6"><label for="password_confirmation"
              class="form-label {{ isset($mahasiswa) ? '' : 'required' }}">Konfirmasi Password</label><input
              type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ isset($mahasiswa) ? '' : 'required' }}></div>
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
        <p class="text-muted">Pastikan semua data yang ditandai * telah terisi dengan benar.</p>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" id="submit-button"><i
              class="fas fa-save me-2"></i>Simpan</button>
          <a href="{{ route('admin.mahasiswas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
      </div>
    </div>
  </div>
</div>