@extends('events.layout')

@section('events')
<div class="mb-20"><h2 class="text-white mb-5">{{ __('messages.academic_calendar') }}</h2><p class="text-fade">{{ __('messages.academic_calendar_help') }}</p></div>
<div class="row">
@forelse($events as $event)
    <div class="col-md-6 col-xl-4">
        <div class="event-card p-20 mb-20 h-100">
            <div class="d-flex justify-content-between mb-10"><span class="badge badge-info">{{ __('messages.' . $event->type) }}</span><small class="text-fade">{{ $event->starts_at->format('d M Y') }}</small></div>
            <h4 class="text-white">{{ $event->title }}</h4>
            <p class="text-info mb-5">{{ $event->starts_at->format('d M Y H:i') }}@if($event->ends_at) - {{ $event->ends_at->format('d M Y H:i') }}@endif</p>
            @if($event->location)<p class="text-fade mb-10"><i class="ti-location-pin"></i> {{ $event->location }}</p>@endif
            @if($event->studentClass)<small class="text-fade d-block mb-10">{{ $event->studentClass->name }}</small>@endif
            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_event') }}</a>
        </div>
    </div>
@empty
    <div class="col-12"><div class="event-card p-50 text-center"><h4>{{ __('messages.no_events') }}</h4><p class="text-fade">{{ __('messages.no_events_help') }}</p></div></div>
@endforelse
</div>
@endsection
