{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - SMS ERP</title>

    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
</head>

<body class="hold-transition theme-primary bg-gradient-primary">
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">
            <div class="col-12">
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-4 col-md-5 col-12">
                        <div class="content-top-agile p-10 text-center">
                            <img height="80" src="{{ asset('upload/shikkha.png') }}" alt="">
                            <h2 class="text-white">Create Account</h2>
                            <p class="text-white-50">Register to get started</p>
                        </div>

                        <div class="p-30 rounded30 box-shadowed">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Name -->
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white">
                                                <i class="ti-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control bg-transparent text-white"
                                            placeholder="Full Name" required autofocus>
                                    </div>
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white">
                                                <i class="ti-email"></i>
                                            </span>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="form-control bg-transparent text-white"
                                            placeholder="Email Address" required>
                                    </div>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white">
                                                <i class="ti-lock"></i>
                                            </span>
                                        </div>
                                        <input type="password" id="password" name="password"
                                            class="form-control bg-transparent text-white"
                                            placeholder="Password" required>
                                    </div>
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white">
                                                <i class="ti-check-box"></i>
                                            </span>
                                        </div>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control bg-transparent text-white"
                                            placeholder="Confirm Password" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info btn-rounded mt-10">
                                            REGISTER
                                        </button>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <p class="text-white">
                                            Already have an account?
                                            <a href="{{ route('login') }}" class="text-info ml-5">Sign In</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/js/vendors.min.js') }}"></script>

    @if($errors->any())
    <script>
        @foreach($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    </script>
    @endif
</body>
</html>
