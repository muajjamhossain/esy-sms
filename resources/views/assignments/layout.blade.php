@extends('admin.admin_master')

@section('title', __('messages.assignments'))

@section('admin')
<style>
    .assignment-shell { padding: 30px 15px; }
    .assignment-card { border: 1px solid rgba(255,255,255,.08); border-radius: 8px; }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <section class="content assignment-shell">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-20">
                <h3 class="text-white">{{ __('messages.assignments') }}</h3>
                <div>
                    <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-primary mr-5">{{ __('messages.all_assignments') }}</a>
                    @if(! in_array(strtolower((string) (auth()->user()->usertype ?? '')), ['student']))
                        <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-primary">{{ __('messages.create_assignment') }}</a>
                    @endif
                </div>
            </div>
            @if(session('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            @yield('assignments')
        </section>
    </div>
</div>
@endsection
