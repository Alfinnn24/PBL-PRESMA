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
            <a href="{{ url('/notifikasi') }}" class="nav-link {{ $activeMenu == 'notifikasi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-award"></i>
                <p>Notifikasi</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/prestasi') }}" class="nav-link {{ $activeMenu == 'verifprestasi' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-award"></i>
                <p>Pencatatan Prestasi</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/rekomendasi') }}"
                class="nav-link {{ $activeMenu == 'rekomendasiLomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-trophy"></i>
                <p>Daftar Lomba</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/pendaftaran') }}"
                class="nav-link {{ $activeMenu == 'pendaftaranlomba' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-folder-open"></i>
                <p>History Lomba</p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                <i class="nav-icon fas fa-solid fa-futbol"></i>
                <p>user profile</p>
            </a>
        </li> --}}
    </ul>
</nav>