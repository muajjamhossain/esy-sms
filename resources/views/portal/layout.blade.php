<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.portal') }} - SMS ERP</title>
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
    <style>
        body { background: #151a2c; color: #fff; }
        .portal-shell { max-width: 1100px; margin: 0 auto; padding: 30px 15px; }
        .portal-card { background: #20283d; border: 1px solid #303b58; border-radius: 8px; }
        .portal-message { max-width: 78%; }
        .portal-message.mine { margin-left: auto; background: #0f5ef7; }
        [dir="rtl"] .portal-message.mine { margin-left: 0; margin-right: auto; }
        [dir="rtl"] .mr-2 { margin-left: .5rem !important; margin-right: 0 !important; }
    </style>
</head>
<body>
<div class="portal-shell">
    <nav class="d-flex flex-wrap align-items-center justify-content-between mb-30">
        <div>
            <a href="{{ route('dashboard') }}" class="text-white font-size-20 mr-15"><i class="ti-home"></i></a>
            <strong>{{ __('messages.portal') }}</strong>
        </div>
        <div class="mt-10 mt-md-0">
            <a href="{{ route('portal.index') }}" class="btn btn-sm btn-outline-light mr-2">{{ __('messages.my_conversations') }}</a>
            <a href="{{ route('portal.conversations.create') }}" class="btn btn-sm btn-primary">{{ __('messages.ask_question') }}</a>
        </div>
    </nav>
    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    @yield('portal')
</div>
</body>
</html>
