<div class="sidebar">
        <div class="sidebar-header">
                <h2><i class="fas fa-graduation-cap me-2"></i> Administrator</h2>
        </div>
        <nav class="sidebar-nav">
                <ul>
                        <li>
                                <a href="{{ route('admin.dashboard') }}"
                                        class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.dosens.index') }}"
                                        class="{{ Request::routeIs('admin.dosens.*') ? 'active' : '' }}">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>Data Dosen</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.mahasiswas.index') }}"
                                        class="{{ Request::routeIs('admin.mahasiswas.*') ? 'active' : '' }}">
                                        <i class="fas fa-user-graduate"></i>
                                        <span>Data Mahasiswa</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.mataKuliahs.index') }}"
                                        class="{{ Request::routeIs('admin.mataKuliahs.*') ? 'active' : '' }}">
                                        <i class="fas fa-book"></i>
                                        <span>Mata Kuliah</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.jadwalKuliahs.index') }}"
                                        class="{{ Request::routeIs('admin.jadwalKuliahs.*') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Jadwal Kuliah</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.pengampuMataKuliah.index') }}"
                                        class="{{ Request::routeIs('admin.pengampuMataKuliah.*') ? 'active' : '' }}">
                                        <i class="fas fa-tasks"></i>
                                        <span>Jadwal Mengajar</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.nilaiMahasiswas.index') }}"
                                        class="{{ Request::routeIs('admin.nilaiMahasiswas.*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list"></i>
                                        <span>Nilai Mahasiswa</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.absenDosens.index') }}"
                                        class="{{ Request::routeIs('admin.absenDosens.*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-check"></i>
                                        <span>Presensi Dosen</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.kelolaPresensi.index') }}"
                                        class="{{ Request::routeIs('admin.kelolaPresensi.*') ? 'active' : '' }}">
                                        <i class="fas fa-user-check"></i>
                                        <span>Presensi Mahasiswa</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('admin.changePasswordForm') }}"
                                        class="{{ Request::routeIs('admin.changePasswordForm') ? 'active' : '' }}">
                                        <i class="fas fa-cog"></i>
                                        <span>Ubah Password</span>
                                </a>
                        </li>
                        <li>
                                <a href="{{ route('logout') }}" style="background: var(--bs-danger)"
                                        onclick=" event.preventDefault(); document.getElementById('logout-form').submit();">
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