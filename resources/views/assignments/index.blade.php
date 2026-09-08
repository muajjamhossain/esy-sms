@extends('assignments.layout')

@section('assignments')
<div class="d-flex justify-content-between align-items-center mb-20">
    <div>
        <h2 class="text-white mb-5">{{ __('messages.assignments') }}</h2>
        <p class="text-fade">{{ __('messages.assignment_help') }}</p>
    </div>
    <span class="badge badge-info badge-lg">{{ $assignments->count() }}</span>
</div>
<div class="row">
@forelse($assignments as $assignment)
    <div class="col-md-6 col-xl-4">
        <div class="assignment-card p-20 mb-20 h-100">
            <span class="badge badge-primary mb-10">{{ $assignment->studentClass->name ?? __('messages.all_classes') }}</span>
            <h4 class="text-white">{{ $assignment->title }}</h4>
            <p class="text-fade">{{ \Illuminate\Support\Str::limit($assignment->instructions, 120) }}</p>
            <div class="small text-fade mb-15">
                {{ $assignment->subject->name ?? __('messages.general') }}
                @if($assignment->due_date) · {{ __('messages.due') }}: {{ $assignment->due_date->format('d M Y') }} @endif
            </div>
            <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_assignment') }}</a>
        </div>
    </div>
@empty
    <div class="col-12"><div class="assignment-card p-50 text-center"><h4>{{ __('messages.no_assignments') }}</h4><p class="text-fade">{{ __('messages.no_assignments_help') }}</p></div></div>
@endforelse
</div>
@endsection
