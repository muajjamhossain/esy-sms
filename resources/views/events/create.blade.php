@extends('events.layout')

@section('events')
<div class="event-card p-25">
    <h3 class="text-white mb-20">{{ __('messages.create_event') }}</h3>
    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group"><label>{{ __('messages.title') }}</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.event_type') }}</label><select name="type" class="form-control">@foreach(['event','holiday','exam','meeting'] as $type)<option value="{{ $type }}">{{ __('messages.' . $type) }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.audience') }}</label><select name="audience" class="form-control">@foreach(['everyone','student','teacher','staff'] as $audience)<option value="{{ $audience }}">{{ __('messages.' . $audience) }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.starts_at') }}</label><input type="datetime-local" name="starts_at" class="form-control" required></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.ends_at') }}</label><input type="datetime-local" name="ends_at" class="form-control"></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.class') }}</label><select name="class_id" class="form-control"><option value="">{{ __('messages.all_classes') }}</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
            <div class="col-md-12 form-group"><label>{{ __('messages.location') }}</label><input name="location" class="form-control" value="{{ old('location') }}"></div>
        </div>
        <div class="form-group"><label>{{ __('messages.description') }}</label><textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea></div>
        <button class="btn btn-primary">{{ __('messages.save_event') }}</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
    </form>
</div>
@endsection
