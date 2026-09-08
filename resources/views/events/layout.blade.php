<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.events') }} - SMS ERP</title>
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
    <style>
        body { background: #151a2c; color: #fff; }
        .event-shell { max-width: 1100px; margin: 0 auto; padding: 30px 15px; }
        .event-card { background: #20283d; border: 1px solid #303b58; border-radius: 8px; }
    </style>
</head>
<body>
<div class="event-shell">
    <nav class="d-flex flex-wrap align-items-center justify-content-between mb-30">
        <div><a href="{{ route('dashboard') }}" class="text-white font-size-20 mr-15"><i class="ti-home"></i></a><strong>{{ __('messages.events') }}</strong></div>
        <div class="mt-10 mt-md-0">
            <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-light mr-2">{{ __('messages.academic_calendar') }}</a>
            @if(in_array(strtolower((string) (auth()->user()->usertype ?? '')), ['admin', 'employee', 'teacher', 'staff']))
                <a href="{{ route('events.create') }}" class="btn btn-sm btn-primary">{{ __('messages.create_event') }}</a>
            @endif
        </div>
    </nav>
    @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
    @yield('events')
</div>
</body>
</html>
