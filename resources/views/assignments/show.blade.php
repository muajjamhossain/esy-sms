@extends('assignments.layout')

@section('assignments')
<div class="assignment-card p-25 mb-20">
    <div class="d-flex justify-content-between flex-wrap">
        <div><h2 class="text-white">{{ $assignment->title }}</h2><p class="text-fade">{{ $assignment->studentClass->name ?? __('messages.all_classes') }} · {{ $assignment->subject->name ?? __('messages.general') }}</p></div>
        @if($assignment->due_date)<span class="badge badge-warning h-25">{{ __('messages.due') }}: {{ $assignment->due_date->format('d M Y') }}</span>@endif
    </div>
    <hr class="border-secondary">
    <div style="white-space: pre-wrap;">{{ $assignment->instructions }}</div>
</div>

@if($submission && $submission->student_id === auth()->id())
    <div class="assignment-card p-25 mb-20">
        <h4 class="text-white">{{ __('messages.your_submission') }}</h4>
        <p style="white-space: pre-wrap;">{{ $submission->answer }}</p>
        @if($submission->marks !== null)<span class="badge badge-success">{{ __('messages.marks') }}: {{ $submission->marks }}</span>@endif
        @if($submission->feedback)<p class="text-info mt-15">{{ $submission->feedback }}</p>@endif
    </div>
@elseif(strtolower((string) (auth()->user()->usertype ?? '')) === 'student')
    <div class="assignment-card p-25 mb-20">
        <h4 class="text-white">{{ __('messages.submit_assignment') }}</h4>
        <form method="POST" action="{{ route('assignments.submit', $assignment) }}">@csrf<textarea name="answer" class="form-control mb-15" rows="8" required></textarea><button class="btn btn-primary">{{ __('messages.submit_assignment') }}</button></form>
    </div>
@endif

@if($assignment->created_by === auth()->id() || strtolower((string) (auth()->user()->role ?? '')) === 'admin')
    <div class="assignment-card p-25">
        <h4 class="text-white">{{ __('messages.submissions') }} ({{ $assignment->submissions->count() }})</h4>
        @forelse($assignment->submissions as $item)
            <div class="border-bottom border-secondary py-15">
                <strong>{{ $item->student->name }}</strong><p class="mt-5" style="white-space: pre-wrap;">{{ $item->answer }}</p>
                <form method="POST" action="{{ route('assignments.feedback', [$assignment, $item]) }}" class="row">@csrf
                    <div class="col-md-2"><input name="marks" type="number" min="0" max="100" step="0.01" class="form-control" placeholder="{{ __('messages.marks') }}" value="{{ $item->marks }}"></div>
                    <div class="col-md-8"><input name="feedback" class="form-control" placeholder="{{ __('messages.feedback') }}" value="{{ $item->feedback }}"></div>
                    <div class="col-md-2"><button class="btn btn-success">{{ __('messages.save_feedback') }}</button></div>
                </form>
            </div>
        @empty <p class="text-fade">{{ __('messages.no_submissions') }}</p> @endforelse
    </div>
@endif
@endsection
