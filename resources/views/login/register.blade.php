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
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('asset_login/image/auth/05.png') }}" class="img-fluid gradient-main animated-scaleX" alt="images">
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent auth-card shadow-none d-flex justify-content-center mb-0">
                                <div class="card-body">
                                    <a href="#" class="navbar-brand d-flex align-items-center mb-3">
                                        <!--Logo start-->
                                        <img src="{{ asset('logo/logo.png') }}" alt="logo" height="30">
                                        <!--logo End-->
                                        <h4 class="logo-title ms-3">PT Toya Indo Manunggal</h4>
                                    </a>
                                    <h2 class="mb-2 text-center">Sign Up</h2>
                                    <p class="text-center">Create your Toya account.</p>
                                    <form class="needs-validation" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="full-name" class="form-label">Nama Lengkap</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="full-name" name="name" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                                                    @error('username')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="jabatan" class="form-label">Jabatan</label>
                                                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>
                                                    @error('jabatan')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="file" class="form-label">Foto</label>
                                                    <input type="file" class="form-control @error('picture') is-invalid @enderror" id="file" name="picture" accept="image/*" required>
                                                    @error('picture')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                                                    <div id="passwordHelpBlock" class="form-text">
                                                        Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, dan angka.
                                                    </div>
                                                    @error('password')
                                                        <span class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="confirm-password" class="form-label">Confirm Password</label>
                                                    <input type="password" class="form-control" id="confirm-password" name="password_confirmation" required autocomplete="new-password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Sign Up</button>
                                        </div>
                                        <p class="mt-3 text-center">
                                                Already have an Account <a href="{{ route('login') }}" class="text-underline">Sign In</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sign-bg sign-bg-right">
                        <svg width="280" height="230" viewBox="0 0 421 359" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.05">
                                <rect x="-15.0845" y="154.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -15.0845 154.773)" fill="#3A57E8"/>
                                <rect x="149.47" y="319.328" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 149.47 319.328)" fill="#3A57E8"/>
                                <rect x="203.936" y="99.543" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 203.936 99.543)" fill="#3A57E8"/>
                                <rect x="204.316" y="-229.172" width="543" height="77.5714" rx="38.7857" transform="rotate(45 204.316 -229.172)" fill="#3A57E8"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('asset_login/js/hope-ui.js') }}" defer></script>
</body>
</html>
