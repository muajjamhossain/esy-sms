@extends('notices.layout')

@section('notices')
<div class="notice-card p-30">
    <div class="d-flex justify-content-between flex-wrap mb-15"><span class="badge badge-primary">{{ __('messages.' . $notice->audience) }}</span><span class="text-fade">{{ optional($notice->published_at)->format('d M Y H:i') }}</span></div>
    <h2 class="text-white">{{ $notice->title }}</h2>
    <p class="text-info">{{ __('messages.published_by') }}: {{ $notice->creator->name }}</p>
    @if($notice->studentClass)<p class="text-fade">{{ __('messages.class') }}: {{ $notice->studentClass->name }}</p>@endif
    <hr class="border-secondary">
    <div style="white-space: pre-wrap;">{{ $notice->body }}</div>
</div>
@endsection
