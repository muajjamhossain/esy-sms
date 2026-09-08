@extends('portal.layout')

@section('portal')
@php
    $otherUser = $conversation->created_by === auth()->id() ? $conversation->participant : $conversation->creator;
@endphp
<div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
    <div>
        <h3 class="text-white mb-5">{{ $conversation->subject }}</h3>
        <span class="text-fade">{{ __('messages.with') }} {{ $otherUser->name }}</span>
    </div>
    @if($conversation->status === 'open')
        <form method="POST" action="{{ route('portal.conversations.close', $conversation) }}">
            @csrf
            <button class="btn btn-sm btn-outline-warning">{{ __('messages.close_conversation') }}</button>
        </form>
    @endif
</div>

<div class="portal-card p-20 mb-20">
    @foreach($conversation->messages as $message)
        <div class="portal-message {{ $message->user_id === auth()->id() ? 'mine' : '' }} rounded p-15 mb-15">
            <div class="d-flex justify-content-between mb-5">
                <strong>{{ $message->user->name }}</strong>
                <small>{{ $message->created_at->diffForHumans() }}</small>
            </div>
            <div style="white-space: pre-wrap;">{{ $message->body }}</div>
        </div>
    @endforeach
</div>

@if($conversation->status === 'open')
    <div class="portal-card p-20">
        <form method="POST" action="{{ route('portal.conversations.reply', $conversation) }}">
            @csrf
            <textarea name="body" class="form-control mb-15" rows="4" maxlength="5000" placeholder="{{ __('messages.write_reply') }}" required></textarea>
            <button class="btn btn-primary">{{ __('messages.send_reply') }}</button>
        </form>
    </div>
@endif
@endsection
