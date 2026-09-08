@extends('notices.layout')

@section('notices')
<div class="mb-20"><h2 class="text-white mb-5">{{ __('messages.notice_board') }}</h2><p class="text-fade">{{ __('messages.notice_board_help') }}</p></div>
<div class="row">
@forelse($notices as $notice)
    <div class="col-md-6 col-xl-4">
        <div class="notice-card p-20 mb-20 h-100">
            <div class="d-flex justify-content-between mb-10"><span class="badge badge-primary">{{ __('messages.' . $notice->audience) }}</span><small class="text-fade">{{ optional($notice->published_at)->format('d M Y') }}</small></div>
            <h4 class="text-white">{{ $notice->title }}</h4>
            <p class="text-fade">{{ \Illuminate\Support\Str::limit($notice->body, 140) }}</p>
            @if($notice->studentClass)<small class="text-info d-block mb-10">{{ $notice->studentClass->name }}</small>@endif
            <a href="{{ route('notices.show', $notice) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.read_notice') }}</a>
        </div>
    </div>
@empty
    <div class="col-12"><div class="notice-card p-50 text-center"><h4>{{ __('messages.no_notices') }}</h4><p class="text-fade">{{ __('messages.no_notices_help') }}</p></div></div>
@endforelse
</div>
@endsection
