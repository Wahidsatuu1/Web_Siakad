<div class="sidebar">
  <div class="sidebar-header">
    <h2><i class="fas fa-user-graduate me-2"></i> Mahasiswa</h2>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <li>
        <a href="{{ route('mahasiswa.dashboard') }}"
          class="{{ Request::routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mahasiswa.lihatJadwalKuliah') }}"
          class="{{ Request::routeIs('mahasiswa.lihatJadwalKuliah') ? 'active' : '' }}">
          <i class="fas fa-calendar-day"></i>
          <span>Jadwal Kuliah</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mahasiswa.lihatKHS') }}"
          class="{{ Request::routeIs('mahasiswa.lihatKHS') ? 'active' : '' }}">
          <i class="fas fa-file-alt"></i>
          <span>Kartu Hasil Studi</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mahasiswa.lihatKRS') }}"
          class="{{ Request::routeIs('mahasiswa.lihatKRS') ? 'active' : '' }}">
          <i class="fas fa-clipboard-check"></i>
          <span>KRS</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mahasiswa.lihatRangkumanNilai') }}"
          class="{{ Request::routeIs('mahasiswa.lihatRangkumanNilai') ? 'active' : '' }}">
          <i class="fas fa-chart-line"></i>
          <span>Rangkuman Nilai</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mahasiswa.presensi.form') }}"
          class="{{ Request::routeIs('mahasiswa.presensi.form') ? 'active' : '' }}">
          <i class="bi bi-person-bounding-box"></i>
          <span>Presensi</span>
        </a>
      </li>
      <li>
        <a href="{{ route('mahasiswa.lihatDetailPribadi') }}"
          class="{{ Request::routeIs('mahasiswa.lihatDetailPribadi') ? 'active' : '' }}">
          <i class="bi bi-person-vcard-fill"></i>
          <span>Detail Pribadi</span>
        </a>
      </li>
      {{--- New Settings/Change Password Menu Item for Mahasiswa ---}}
      <li>
        <a href="{{ route('mahasiswa.change_password_form') }}" {{-- Anda perlu membuat route ini --}}
          class="{{ Request::routeIs('mahasiswa.change_password_form') ? 'active' : '' }}">
          <i class="fas fa-cog"></i> {{-- Ikon roda gigi untuk Settings --}}
          <span>Ubah Password</span>
        </a>
      </li>
      {{---------------------------------------------------}}
      <li>
        <a href="{{ route('logout') }}" style="background: var(--bs-danger)"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </li>
    </ul>
  </nav>
</div>