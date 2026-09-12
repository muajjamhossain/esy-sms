@extends('admin.admin_master')
@section('admin')
<div class="content-wrapper"><div class="container-full"><section class="content">
    <div class="d-flex justify-content-between align-items-center mb-20">
        <h3>{{ __('messages.exam_papers') }}</h3>
        @if(strtolower((string) (auth()->user()->role ?: auth()->user()->usertype)) !== 'student')
            <a href="{{ route('exam-papers.create') }}" class="btn btn-primary">{{ __('messages.create_exam_paper') }}</a>
        @endif
    </div>
    <div class="box"><div class="box-body table-responsive">
        <p>{{ __('messages.exam_paper_help') }}</p>
        <table class="table table-bordered">
            <thead><tr><th>{{ __('messages.title') }}</th><th>{{ __('messages.class') }}</th><th>{{ __('messages.subject') }}</th><th>{{ __('messages.max_marks') }}</th><th></th></tr></thead>
            <tbody>
            @forelse($papers as $paper)
                <tr><td>{{ $paper->title }}</td><td>{{ $paper->studentClass->name ?? __('messages.all_classes') }}</td><td>{{ $paper->subject->name ?? __('messages.general') }}</td><td>{{ $paper->max_marks }}</td><td><a class="btn btn-sm btn-info" href="{{ route('exam-papers.show', $paper) }}">{{ __('messages.view_assignment') }}</a></td></tr>
            @empty <tr><td colspan="5">{{ __('messages.no_assignments') }}</td></tr> @endforelse
            </tbody>
        </table>
    </div></div>
</section></div></div>
@endsection
