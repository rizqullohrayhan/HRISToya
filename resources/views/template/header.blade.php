<div class="main-header">
    <div class="main-header-logo">
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
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" data-background-color="dark">
        <div class="container-fluid">

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                        aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="{{ asset ('upload/profile_picture/'.Auth::user()->picture) }}" alt="profile_picture" class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span> <span class="fw-bold">{{ Auth::user()->username }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            {{-- <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="{{ asset ('profile_picture/'.Auth::user()->picture) }}" alt="image profile"
                                            class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->username }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <a href="profile.html" class="btn btn-xs btn-secondary btn-sm">View
                                            Profile</a>
                                    </div>
                                </div>
                            </li> --}}
                            <li>
                                {{-- <div class="dropdown-divider"></div> --}}
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="icon-user"></i>&nbsp;My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.change') }}">
                                    <i class="icon-lock"></i>&nbsp;Ganti Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{'logout'}}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                                >
                                    <i class="icon-logout"></i>&nbsp;Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
