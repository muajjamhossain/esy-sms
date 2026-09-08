@extends('events.layout')

@section('events')
<div class="event-card p-30">
    <div class="d-flex justify-content-between flex-wrap mb-15"><span class="badge badge-info">{{ __('messages.' . $event->type) }}</span><span class="text-fade">{{ $event->starts_at->format('d M Y H:i') }}</span></div>
    <h2 class="text-white">{{ $event->title }}</h2>
    <p class="text-info">{{ __('messages.published_by') }}: {{ $event->creator->name }}</p>
    <p class="text-fade">{{ $event->starts_at->format('d M Y H:i') }}@if($event->ends_at) - {{ $event->ends_at->format('d M Y H:i') }}@endif</p>
    @if($event->location)<p class="text-fade"><i class="ti-location-pin"></i> {{ $event->location }}</p>@endif
    @if($event->studentClass)<p class="text-fade">{{ __('messages.class') }}: {{ $event->studentClass->name }}</p>@endif
    <hr class="border-secondary">
    <div style="white-space: pre-wrap;">{{ $event->description }}</div>
</div>
@endsection
