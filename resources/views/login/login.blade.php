<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME') }} - PT Toya Indo Manunggal</title>

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('asset_login/css/core/libs.min.css') }}" />

    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('asset_login/css/hope-ui.min.css?v=1.1.0') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('asset_login/css/custom.min.css?v=1.1.0') }}" />
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body">
                                    <a href="../../dashboard/index.html"
                                        class="navbar-brand d-flex align-items-center mb-3">
                                        <!--Logo start-->
                                        <img src="{{ asset('logo/logo.png') }}" alt="logo" height="30">
                                        <!--logo End-->
                                        <h4 class="logo-title ms-3">PT Toya Indo Manunggal</h4>
                                    </a>
                                    <h2 class="mb-2 text-center">Sign In</h2>
                                    <p class="text-center">Login to stay connected.</p>
                                    {{-- @error('username')
                                        <div class="alert alert-danger" role="alert" style="margin-bottom: 0; padding: 0; text-align: center; border:none;">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @enderror --}}
                                    <form class="needs-validation" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Username or Email</label>
                                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="email"
                                                        aria-describedby="email" placeholder=" " name="username" value="{{ old('username') }}" required autofocus>
                                                    @error('username')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        aria-describedby="password" placeholder=" " name="password" required autocomplete="current-password">
                                                    @error('password')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12 d-flex justify-content-between">
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" class="form-check-input" id="customCheck1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="customCheck1">Remember
                                                        Me</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Sign In</button>
                                        </div>
                                        <p class="mt-3 text-center">
                                            Don’t have an account? <a href="{{ route('register') }}" class="text-underline">Click
                                                here to sign up.</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sign-bg">
                        <svg width="280" height="230" viewBox="0 0 431 398" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.05">
                                <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
                                <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
                                <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857"
                                    transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
                                <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('asset_login/image/auth/05.png') }}" class="img-fluid gradient-main animated-scaleX"
                        alt="images">
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('asset_login/js/core/libs.min.js') }}"></script>
    <script src="{{ asset('asset_login/js/core/libs.min.js') }}"></script>
    <script src="{{ asset('asset_login/js/plugins/fslightbox.js') }}"></script>
    <script src="{{ asset('asset_login/js/plugins/setting.js') }}"></script>
    <script src="{{ asset('asset_login/js/plugins/form-wizard.js') }}"></script>
    <script src="{{ asset('asset_login/js/hope-ui.js') }}" defer></script>
</body>

</html>
