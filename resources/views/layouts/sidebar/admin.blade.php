<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-header">Data Pengguna</li>
        <li class="nav-item">
            <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Data User</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/prestasi') }}" class="nav-link {{ $activeMenu == 'verifprestasi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-award"></i>
                <p>Verifikasi Prestasi</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/laporan-prestasi') }}"
                class="nav-link {{ $activeMenu == 'laporanprestasi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-file-contract"></i>
                <p>Laporan & Analisis</p>
            </a>
        </li>


        <li class="nav-header">Manejemen Akademik</li>
        <li class="nav-item">
            <a href="{{ url('/periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-calendar-minus"></i>
                <p>Periode Semester</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/program_studi') }}" class="nav-link {{ $activeMenu == 'prodi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-school"></i>
                <p>Program Studi</p>
            </a>
        </li>
        <li class="nav-header">Manejemen Perlombaan</li>
        <li class="nav-item">
            <a href="{{ url('/lomba') }}" class="nav-link {{ $activeMenu == 'lomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-folder-open"></i>
                <p>Data Lomba</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'veriflomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-check"></i>
                <p>Verifikasi Lomba</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/rekomendasi') }}"
                class="nav-link {{ $activeMenu == 'rekomendasiLomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-futbol"></i>
                <p>Rekomandasi Lomba</p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'userprofile' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-futbol"></i>
                <p>user profile</p>
            </a>
        </li> --}}
    </ul>
</nav>