<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Menu by role -->
    @if (Auth::user()->getRole() == 'admin')
        @include('layouts.sidebar.admin')
    @elseif (Auth::user()->getRole() == 'mahasiswa')
        @include('layouts.sidebar.mahasiswa')
    @elseif (Auth::user()->getRole() == 'dosen')
        @include('layouts.sidebar.dosen')
    @endif
</div>
