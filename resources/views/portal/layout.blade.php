@extends('admin.admin_master')

@section('title', __('messages.portal'))

@section('admin')
<style>
    .portal-shell { padding: 30px 15px; }
    .portal-card { border: 1px solid rgba(255,255,255,.08); border-radius: 8px; }
    .portal-message { max-width: 78%; }
    .portal-message.mine { margin-left: auto; }
    [dir="rtl"] .portal-message.mine { margin-left: 0; margin-right: auto; }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <section class="content portal-shell">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-20">
                <h3 class="text-white">{{ __('messages.portal') }}</h3>
                <a href="{{ route('portal.conversations.create') }}" class="btn btn-sm btn-primary">{{ __('messages.ask_question') }}</a>
            </div>
            @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            @yield('portal')
        </section>
    </div>
</div>
@endsection
