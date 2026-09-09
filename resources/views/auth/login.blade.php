<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('backend/images/logo/fateha.jpeg') }}">
    <title>Fateha School/Madrasa - Log in</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #063f3b 0%, #0b5d57 58%, #d7ad54 160%);
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
        [dir="rtl"] .text-right {
            text-align: left !important;
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
                            <img class="fateha-auth-logo" src="{{ asset('backend/images/logo/fateha.jpeg') }}" alt="Fateha School/Madrasa">
                            <h2 class="text-white mt-3">Fateha School/Madrasa</h2>
                            <p class="text-white-50">{{ __('messages.login_subtitle') }}</p>
                        </div>
                        <div class="text-center mb-15">
                            <a class="btn btn-sm btn-light" href="{{ route('language.switch', 'en') }}">English</a>
                            <a class="btn btn-sm btn-light" href="{{ route('language.switch', 'bn') }}">বাংলা</a>
                            <a class="btn btn-sm btn-light" href="{{ route('language.switch', 'ar') }}">العربية</a>
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
                                            placeholder="{{ __('messages.email_address') }}" required autofocus>
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
                                            placeholder="{{ __('messages.password') }}" required>
                                    </div>
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="checkbox text-white">
                                            <input type="checkbox" name="remember" id="remember">
                                            <label for="remember">{{ __('messages.remember_me') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="fog-pwd text-right">
                                            <a href="{{ route('password.request') }}" class="text-white hover-info">
                                                <i class="ion ion-locked"></i> {{ __('messages.forgot_password') }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info btn-rounded mt-10 px-4">
                                            {{ __('messages.sign_in') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Social Login -->
                            <div class="text-center text-white">
                                <p class="mt-20">- {{ __('messages.sign_with') }} -</p>
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
                                    {{ __('messages.no_account') }}
                                    <a href="{{ route('register') }}" class="text-info ml-5">{{ __('messages.sign_up') }}</a>
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
