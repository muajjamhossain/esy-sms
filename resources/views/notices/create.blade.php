@extends('notices.layout')

@section('notices')
<div class="notice-card p-25">
    <h3 class="text-white mb-20">{{ __('messages.create_notice') }}</h3>
    <form method="POST" action="{{ route('notices.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group"><label>{{ __('messages.title') }}</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.audience') }}</label><select name="audience" class="form-control">@foreach(['everyone','student','teacher','staff'] as $audience)<option value="{{ $audience }}">{{ __('messages.' . $audience) }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.class') }}</label><select name="class_id" class="form-control"><option value="">{{ __('messages.all_classes') }}</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
        </div>
        <div class="form-group"><label>{{ __('messages.message') }}</label><textarea name="body" class="form-control" rows="8" required>{{ old('body') }}</textarea></div>
        <div class="row"><div class="col-md-6 form-group"><label>{{ __('messages.publish_at') }}</label><input type="datetime-local" name="published_at" class="form-control"></div><div class="col-md-6 form-group"><label>{{ __('messages.expires_at') }}</label><input type="datetime-local" name="expires_at" class="form-control"></div></div>
        <button class="btn btn-primary">{{ __('messages.publish_notice') }}</button>
        <a href="{{ route('notices.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
    </form>
</div>
@endsection
