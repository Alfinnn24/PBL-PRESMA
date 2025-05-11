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
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'verifprestasi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-award"></i>
                <p>Verifikasi Prestasi</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'laporan' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-file-contract"></i>
                <p>Laporan & Analisis</p>
            </a>
        </li>
        <li class="nav-header">Manejemen Akademik</li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-calendar-minus"></i>
                <p>Periode Semester</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'prodi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-school"></i>
                <p>Program Studi</p>
            </a>
        </li>
        <li class="nav-header">Manejemen Perlombaan</li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'datalomba' ? 'active' : '' }}">
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
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'rekomlomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-futbol"></i>
                <p>Rekomandasi Lomba</p>
            </a>
        </li>
    </ul>
</nav>
