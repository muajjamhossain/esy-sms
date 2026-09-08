@extends('portal.layout')

@section('portal')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
    <div>
        <h2 class="text-white mb-5">{{ __('messages.communication_portal') }}</h2>
        <p class="text-fade">{{ __('messages.communication_portal_help') }}</p>
    </div>
    <span class="badge badge-info badge-lg">{{ $unreadCount }} {{ __('messages.unread') }}</span>
</div>

<div class="portal-card p-20">
    @forelse($conversations as $conversation)
        @php
            $otherUser = $conversation->created_by === auth()->id() ? $conversation->participant : $conversation->creator;
            $lastMessage = $conversation->messages->first();
        @endphp
        <a href="{{ route('portal.conversations.show', $conversation) }}" class="d-block text-white border-bottom border-secondary py-15">
            <div class="d-flex justify-content-between">
                <strong>{{ $conversation->subject }}</strong>
                <span class="text-fade small">{{ $conversation->updated_at->diffForHumans() }}</span>
            </div>
            <div class="text-fade mt-5">{{ __('messages.with') }} {{ $otherUser->name }} · {{ $otherUser->role ?: $otherUser->usertype ?: __('messages.member') }}</div>
            @if($lastMessage)
                <div class="mt-5 text-truncate">{{ $lastMessage->body }}</div>
            @endif
            <span class="badge {{ $conversation->status === 'open' ? 'badge-success' : 'badge-secondary' }} mt-5">{{ __('messages.' . $conversation->status) }}</span>
        </a>
    @empty
        <div class="text-center py-50">
            <i class="ti-comments font-size-40 text-primary"></i>
            <h4 class="text-white mt-15">{{ __('messages.no_conversations') }}</h4>
            <p class="text-fade">{{ __('messages.start_question_help') }}</p>
            <a href="{{ route('portal.conversations.create') }}" class="btn btn-primary">{{ __('messages.ask_question') }}</a>
        </div>
    @endforelse
</div>
@endsection
