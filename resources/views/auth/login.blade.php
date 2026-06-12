<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <title>SMS ERP - Log in</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">

    <style>
        .bg-gradient-primary {
            background: linear-gradient(87deg, #fb6340 0, #f5365c 100%);
        }
        .plc-white::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .rounded30 {
            border-radius: 30px;
        }
        .box-shadowed {
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="hold-transition theme-primary bg-gradient-primary"
    style="background-image: url('{{ asset('upload/curjon hall.jpg') }}'); background-repeat: no-repeat; background-position: center; background-size: cover;">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">
            <div class="col-12">
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-4 col-md-5 col-12">
                        <div class="content-top-agile p-10 text-center">
                            <img height="100" width="150" src="{{ asset('upload/shikkha.png') }}" alt="Logo">
                            <h2 class="text-white mt-3">Get started with Us</h2>
                            <p class="text-white-50">Sign in to start your session</p>
                        </div>

                        <div class="p-30 rounded30 box-shadowed b-2 b-dashed" style="border-color: green;">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Field -->
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white">
                                                <i class="ti-user"></i>
                                            </span>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="form-control pl-15 bg-transparent text-white plc-white"
                                            placeholder="Email Address" required autofocus>
                                    </div>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white">
                                                <i class="ti-lock"></i>
                                            </span>
                                        </div>
                                        <input type="password" id="password" name="password"
                                            class="form-control pl-15 bg-transparent text-white plc-white"
                                            placeholder="Password" required>
                                    </div>
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="checkbox text-white">
                                            <input type="checkbox" name="remember" id="remember">
                                            <label for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="fog-pwd text-right">
                                            <a href="{{ route('password.request') }}" class="text-white hover-info">
                                                <i class="ion ion-locked"></i> Forgot password?
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info btn-rounded mt-10 px-4">
                                            SIGN IN
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Social Login -->
                            <div class="text-center text-white">
                                <p class="mt-20">- Sign With -</p>
                                <p class="gap-items-2 mb-20">
                                    <a class="btn btn-social-icon btn-round btn-outline btn-white" href="#">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                    <a class="btn btn-social-icon btn-round btn-outline btn-white" href="#">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                    <a class="btn btn-social-icon btn-round btn-outline btn-white" href="#">
                                        <i class="fa fa-google-plus"></i>
                                    </a>
                                    <a class="btn btn-social-icon btn-round btn-outline btn-white" href="#">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </p>
                            </div>

                            <!-- Register Link -->
                            <div class="text-center">
                                <p class="mt-15 mb-0 text-white">
                                    Don't have an account?
                                    <a href="{{ route('register') }}" class="text-info ml-5">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS -->
    <script src="{{ asset('backend/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

    @if(session('error'))
    <script>
        toastr.error('{{ session('error') }}');
    </script>
    @endif
</body>

</html>
