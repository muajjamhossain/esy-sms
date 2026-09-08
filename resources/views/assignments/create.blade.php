@extends('assignments.layout')

@section('assignments')
<div class="assignment-card p-25">
    <h3 class="text-white mb-20">{{ __('messages.create_assignment') }}</h3>
    <form method="POST" action="{{ route('assignments.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group"><label>{{ __('messages.title') }}</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.class') }}</label><select name="class_id" class="form-control"><option value="">{{ __('messages.all_classes') }}</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.subject') }}</label><select name="subject_id" class="form-control"><option value="">{{ __('messages.general') }}</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select></div>
        </div>
        <div class="form-group"><label>{{ __('messages.instructions') }}</label><textarea name="instructions" class="form-control" rows="7" required>{{ old('instructions') }}</textarea></div>
        <div class="form-group"><label>{{ __('messages.due_date') }}</label><input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}"></div>
        <button class="btn btn-primary">{{ __('messages.publish_assignment') }}</button>
        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
    </form>
</div>
@endsection
