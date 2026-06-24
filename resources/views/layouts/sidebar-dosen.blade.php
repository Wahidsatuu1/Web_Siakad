<div class="sidebar">
  <div class="sidebar-header">
    <h2><i class="fas fa-chalkboard-teacher me-2"></i> Dosen</h2>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <li>
        <a href="{{ route('dosen.dashboard') }}" class="{{ Request::routeIs('dosen.dashboard') ? 'active' : '' }}">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('dosen.lihatJadwalMengajar') }}"
          class="{{ Request::routeIs('dosen.lihatJadwalMengajar') ? 'active' : '' }}">
          <i class="fas fa-calendar-check"></i>
          <span>Jadwal Mengajar</span>
        </a>
      </li>
      <li>
        <a href="{{ route('dosen.kelolaNilaiMahasiswa') }}"
          class="{{ Request::routeIs('dosen.kelolaNilaiMahasiswa') ? 'active' : '' }}">
          <i class="fas fa-edit"></i>
          <span>Kelola Nilai</span>
        </a>
      </li>
      <li>
        <a href="{{ route('dosen.lihatDaftarMahasiswa') }}"
          class="{{ Request::routeIs('dosen.lihatDaftarMahasiswa') ? 'active' : '' }}">
          <i class="fas fa-users"></i>
          <span>Daftar Mahasiswa</span>
        </a>
      </li>
      <li>
        <a href="{{ route('dosen.absen.index') }}" class="{{ Request::routeIs('dosen.absen.index') ? 'active' : '' }}">
          <i class="fas fa-clipboard-check"></i> {{-- Mengganti ikon agar lebih relevan dengan absen --}}
          <span>Presensi</span>
        </a>
      </li>
      {{-- --- New Settings/Change Password Menu Item for Dosen --- --}}
      <li>
        <a href="{{ route('dosen.change_password_form') }}" {{-- Anda perlu membuat route ini --}}
          class="{{ Request::routeIs('dosen.change_password_form') ? 'active' : '' }}">
          <i class="fas fa-cog"></i> {{-- Ikon roda gigi untuk Settings --}}
          <span>Ubah Password</span>
        </a>
      </li>
      {{-- --------------------------------------------------- --}}
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