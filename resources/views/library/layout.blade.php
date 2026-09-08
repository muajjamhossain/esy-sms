@extends('admin.admin_master')

@section('title', __('messages.library'))

@section('admin')
<style>
    .library-shell { padding: 30px 15px; }
    .library-card { border: 1px solid rgba(255,255,255,.08); border-radius: 8px; }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <section class="content library-shell">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-20">
                <h3 class="text-white">{{ __('messages.library') }}</h3>
                @if(in_array(strtolower((string) (auth()->user()->usertype ?? '')), ['admin', 'employee', 'teacher', 'staff']))
                    <a href="{{ route('library.create') }}" class="btn btn-sm btn-primary">{{ __('messages.manage_library') }}</a>
                @endif
            </div>
            @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            @yield('library')
        </section>
    </div>
</div>
@endsection
