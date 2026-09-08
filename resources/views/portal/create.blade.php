@extends('portal.layout')

@section('portal')
<div class="portal-card p-25">
    <h3 class="text-white mb-20">{{ __('messages.ask_question') }}</h3>
    <form method="POST" action="{{ route('portal.conversations.store') }}">
        @csrf
        <div class="form-group">
            <label>{{ __('messages.recipient') }}</label>
            <select name="participant_id" class="form-control" required>
                <option value="">{{ __('messages.choose_recipient') }}</option>
                @foreach($contacts as $contact)
                    <option value="{{ $contact->id }}" {{ old('participant_id') == $contact->id ? 'selected' : '' }}>
                        {{ $contact->name }} ({{ $contact->role ?: $contact->usertype ?: __('messages.member') }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>{{ __('messages.subject') }}</label>
            <input name="subject" class="form-control" value="{{ old('subject') }}" maxlength="150" required>
        </div>
        <div class="form-group">
            <label>{{ __('messages.message') }}</label>
            <textarea name="body" class="form-control" rows="7" maxlength="5000" required>{{ old('body') }}</textarea>
        </div>
        <button class="btn btn-primary">{{ __('messages.send_question') }}</button>
        <a href="{{ route('portal.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
    </form>
</div>
@endsection
