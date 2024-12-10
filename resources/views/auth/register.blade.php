<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('auth.partials._head')

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hope-ui.css?v=1.0') }}">
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('images/auth/05.png') }}" class="img-fluid gradient-main animated-scaleX"
                        alt="images">
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent auth-card shadow-none d-flex justify-content-center mb-0">
                                <div class="card-body">
                                    <a href="#" class="navbar-brand d-flex align-items-center mb-3">
                                        <svg width="30" class="text-primary" viewBox="0 0 30 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                                                transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                                                transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                                                transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                                                transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                                        </svg>
                                        <h4 class="logo-title ms-2">Mini E-Commerce</h4>
                                    </a>
                                    <h2 class="mb-2 text-center">Register</h2>
                                    <p class="text-center">Make an admin account.</p>

                                    <form method="POST" action="{{ route('register.submit') }}"
                                        data-toggle="validator">
                                        {{ csrf_field() }}

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Name</label>

                                                    <input id="name" type="name" name="name"
                                                        value="{{ old('name') }}"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        placeholder="Muhammad Syaugi Shahab" required autofocus>

                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email</label>

                                                    <input id="email" type="email" name="email"
                                                        value="{{ old('email') }}"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        placeholder="20081010107@gmail.com" required autofocus>

                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>

                                                    <input id="password" type="password" name="password"
                                                        value="{{ old('password') }}"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        placeholder="********" required autofocus
                                                        autocomplete="new-password">

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="password-confirm" class="form-label">
                                                        Password Confirmation<span class="text-danger">*</span></label>
                                                    <input id="password-confir" class="form-control" type="password"
                                                        placeholder="********" name="password_confirmation" required
                                                        autocomplete="new-password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Register') }}</button>
                                        </div>

                                        <p class="mt-3 text-center">
                                            Already have an account? <a href="{{ route('login') }}"
                                                class="text-underline">Login</a>
                                        </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
