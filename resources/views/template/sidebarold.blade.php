<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset ('logo/logo.png') }}" alt="navbar brand" class="navbar-brand"
                    height="30" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ Request::is('home*') ? 'active' : '' }}">
                    <a href="{{route('home')}}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @canany(['view absen', 'view aktivitas'])
                <li class="nav-item {{ (Request::is('absen*') || Request::is('aktivitas*')) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#aktivitas">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Aktivitas</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="aktivitas">
                        <ul class="nav nav-collapse">
                            @can('view absen')
                            <li>
                                <a href="{{ route('absen.index') }}">
                                    <span class="sub-item">Absen</span>
                                </a>
                            </li>
                            @endcan
                            @can('view aktivitas')
                            <li>
                                <a href="{{ route('aktivitas.index') }}">
                                    <span class="sub-item">Buku Aktivitas</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
                @can('view voucher')
                <li class="nav-item {{ Request::is('voucher*') ? 'active' : '' }}">
                    <a href="{{route('voucher.index')}}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Pengajuan Voucher</p>
                    </a>
                </li>
                @endcan
                @canany(['view surat ijin', 'view tugas keluar', 'view dinas luar', 'view dinas luar kota', 'view cuti'])
                <li class="nav-item {{ (Request::is('ijin*') || Request::is('tugas-keluar*') || Request::is('dinasluar*') || Request::is('cuti*')) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#surat">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Persuratan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="surat">
                        <ul class="nav nav-collapse">
                            @can('view surat ijin')
                            <li>
                                <a href="{{ route('ijin.index') }}">
                                    <span class="sub-item">Ijin</span>
                                </a>
                            </li>
                            @endcan
                            @can('view tugas keluar')
                            <li>
                                <a href="{{ route('tugas-keluar.index') }}">
                                    <span class="sub-item">Tugas Keluar</span>
                                </a>
                            </li>
                            @endcan
                            @can('view dinas luar')
                            <li>
                                <a href="{{ route('dinasluar.index') }}">
                                    <span class="sub-item">Dinas Luar</span>
                                </a>
                            </li>
                            @endcan
                            @can('view dinas luar kota')
                            <li>
                                <a href="{{ route('dinasluarkota.index') }}">
                                    <span class="sub-item">Dinas Luar Kota</span>
                                </a>
                            </li>
                            @endcan
                            @can('view cuti')
                            <li>
                                <a href="{{ route('cuti.index') }}">
                                    <span class="sub-item">Cuti</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
                @can('view ijin masuk pabrik')
                <li class="nav-item {{ Request::is('ijinpabrik*') ? 'active' : '' }}">
                    <a href="{{route('ijinpabrik.index')}}">
                        <i class="fas fa-receipt"></i>
                        <p>Ijin Masuk Pabrik</p>
                    </a>
                </li>
                @endcan
                @can('view notulen rapat')
                <li class="nav-item {{ Request::is('notulen_rapat*') ? 'active' : '' }}">
                    <a href="{{route('notulen_rapat.index')}}">
                        <i class="fas fa-receipt"></i>
                        <p>Notulen Rapat</p>
                    </a>
                </li>
                @endcan
                @can('view kontrak pengiriman')
                <li class="nav-item {{ Request::is('pengiriman/kontrak*') ? 'active' : '' }}">
                    <a href="{{route('kontrak.index')}}">
                        <i class="fas fa-receipt"></i>
                        <p>Kontrak Pengiriman</p>
                    </a>
                </li>
                @endcan
                @can('view master dokumen')
                <li class="nav-item {{ Request::is('masterdokumen*') ? 'active' : '' }}">
                    <a href="{{route('masterdokumen.index')}}">
                        <i class="fas fa-receipt"></i>
                        <p>Master Dokumen</p>
                    </a>
                </li>
                @endcan
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Setting</h4>
                </li>
                @canany(['view tipe aktivitas', 'view cara aktivitas'])
                <li class="nav-item {{ (Request::is('tipeaktivitas*') || Request::is('caraaktivitas*')) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#data-aktivitas">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Data Aktivitas</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="data-aktivitas">
                        <ul class="nav nav-collapse">
                            @can('view tipe aktivitas')
                            <li>
                                <a href="{{ route('tipeaktivitas.index') }}">
                                    <span class="sub-item">Tipe Aktivitas</span>
                                </a>
                            </li>
                            @endcan
                            @can('view cara aktivitas')
                            <li>
                                <a href="{{ route('caraaktivitas.index') }}">
                                    <span class="sub-item">Cara Aktivitas</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['view kode bank', 'view mata uang', 'view tipe voucher'])
                <li class="nav-item {{ (Request::is('kodeperkiraan*') || Request::is('matauang*') || Request::is('tipevoucher*')) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#data-voucher">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Data Voucher</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="data-voucher">
                        <ul class="nav nav-collapse">
                            @can('view kode perkiraan')
                            <li>
                                <a href="{{ route('kodeperkiraan.index') }}">
                                    <span class="sub-item">Kode Perkiraan</span>
                                </a>
                            </li>
                            @endcan
                            @can('view mata uang')
                            <li>
                                <a href="{{ route('matauang.index') }}">
                                    <span class="sub-item">Mata Uang</span>
                                </a>
                            </li>
                            @endcan
                            @can('view tipe voucher')
                            <li>
                                <a href="{{ route('tipevoucher.index') }}">
                                    <span class="sub-item">Tipe Voucher</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
                @canany(['view macam cuti', 'view cuti bersama'])
                <li class="nav-item {{ (Request::is('macamcuti*') || Request::is('cutibersama*')) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#data-cuti">
                        <i class="fas fa-sticky-note"></i>
                        <p>Data Cuti</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="data-cuti">
                        <ul class="nav nav-collapse">
                            @can('view kode perkiraan')
                            <li>
                                <a href="{{ route('macamcuti.index') }}">
                                    <span class="sub-item">Macam Cuti</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
                @can('view team')
                <li class="nav-item {{ Request::is('team*') ? 'active' : '' }}">
                    <a href="{{route('team.index')}}">
                        <i class="fas fa-users"></i>
                        <p>Team</p>
                    </a>
                </li>
                @endcan
                @can('view rekan')
                <li class="nav-item {{ Request::is('rekan*') ? 'active' : '' }}">
                    <a href="{{route('rekan.index')}}">
                        <i class="fas fa-users"></i>
                        <p>Rekanan</p>
                    </a>
                </li>
                @endcan
                @can('view kantor')
                <li class="nav-item {{ Request::is('kantor*') ? 'active' : '' }}">
                    <a href="{{route('kantor.index')}}">
                        <i class="fas fa-hotel"></i>
                        <p>Kantor</p>
                    </a>
                </li>
                @endcan
                @canany(['view role', 'view permission', 'view user'])
                <li class="nav-item {{ (Request::is('role*') || Request::is('permission*') || Request::is('user*')) ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#role-permission">
                        <i class="far fa-chart-bar"></i>
                        <p>Role-Permission</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="role-permission">
                        <ul class="nav nav-collapse">
                            @can('view role')
                            <li>
                                <a href="{{ route('role.index') }}">
                                    <span class="sub-item">Role</span>
                                </a>
                            </li>
                            @endcan
                            @can('view permission')
                            <li>
                                <a href="{{ route('permission.index') }}">
                                    <span class="sub-item">Permission</span>
                                </a>
                            </li>
                            @endcan
                            @can('view user')
                            <li>
                                <a href="{{ route('user.index') }}">
                                    <span class="sub-item">User</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany
            </ul>
        </div>
    </div>
</div>
