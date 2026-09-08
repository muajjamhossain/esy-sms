@extends('admin.admin_master')

@section('title', __('messages.events'))

@section('admin')
<style>
    .event-shell { padding: 30px 15px; }
    .event-card { border: 1px solid rgba(255,255,255,.08); border-radius: 8px; }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <section class="content event-shell">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-20">
                <h3 class="text-white">{{ __('messages.events') }}</h3>
                @if(in_array(strtolower((string) (auth()->user()->usertype ?? '')), ['admin', 'employee', 'teacher', 'staff']))
                    <a href="{{ route('events.create') }}" class="btn btn-sm btn-primary">{{ __('messages.create_event') }}</a>
                @endif
            </div>
            @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            @yield('events')
        </section>
    </div>
</div>
@endsection
