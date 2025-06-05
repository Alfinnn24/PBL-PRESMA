<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/dosen/bimbingan') }}" class="nav-link {{ $activeMenu == 'datamhs' ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Mahasiswa bimbingan</p>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'verifprestasi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-award"></i>
                <p>Verifikasi Prestasi</p>
            </a>
        </li> -->
        <li class="nav-item">
            <a href="{{ url('dosen/lomba') }}" class="nav-link {{ $activeMenu == 'lomba_dosen' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-folder-open"></i>
                <p>Data Lomba</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/rekomendasi') }}"
                class="nav-link {{ $activeMenu == 'rekomendasiLomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-trophy"></i>
                <p>Rekomendasi Lomba</p>
            </a>
        </li>
    </ul>
</nav>