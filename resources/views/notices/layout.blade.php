@extends('admin.admin_master')

@section('title', __('messages.notices'))

@section('admin')
<style>
    .notice-shell { padding: 30px 15px; }
    .notice-card { border: 1px solid rgba(255,255,255,.08); border-radius: 8px; }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <section class="content notice-shell">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-20">
                <h3 class="text-white">{{ __('messages.notices') }}</h3>
                @if(in_array(strtolower((string) (auth()->user()->usertype ?? '')), ['admin', 'employee', 'teacher', 'staff']))
                    <a href="{{ route('notices.create') }}" class="btn btn-sm btn-primary">{{ __('messages.create_notice') }}</a>
                @endif
            </div>
            @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            @yield('notices')
        </section>
    </div>
</div>
@endsection
