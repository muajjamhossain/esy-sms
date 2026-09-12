@extends('admin.admin_master')
@section('admin')
<div class="content-wrapper"><div class="container-full"><section class="content">
    <div class="box"><div class="box-header"><h3>{{ $examPaper->title }}</h3><p>{{ $examPaper->year->name ?? '' }} · {{ $examPaper->studentClass->name ?? __('messages.all_classes') }} · {{ $examPaper->subject->name ?? __('messages.general') }} · {{ __('messages.max_marks') }}: {{ $examPaper->max_marks }}</p></div><div class="box-body">
        <p><a href="{{ Storage::disk('public')->url($examPaper->question_file) }}" target="_blank">{{ __('messages.question_file') }}</a></p>
        @if($examPaper->answer_key_file)<p><a href="{{ Storage::disk('public')->url($examPaper->answer_key_file) }}" target="_blank">{{ __('messages.answer_key_file') }}</a></p>@endif
    </div></div>
    @if(strtolower((string) (auth()->user()->role ?: auth()->user()->usertype)) === 'student')
        <div class="box"><div class="box-header"><h4>{{ __('messages.submit_answer_sheet') }}</h4></div><div class="box-body">
            @if($submission && $submission->final_marks !== null)<div class="alert alert-success">{{ __('messages.final_marks') }}: {{ $submission->final_marks }}/{{ $examPaper->max_marks }}</div>@endif
            <form method="POST" action="{{ route('exam-papers.submit', $examPaper) }}" enctype="multipart/form-data">@csrf
                <input type="file" name="answer_file" class="form-control mb-15" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                <button class="btn btn-primary">{{ __('messages.submit_answer_sheet') }}</button>
            </form>
        </div></div>
    @else
        <div class="box"><div class="box-header"><h4>{{ __('messages.upload_for_student') }}</h4></div><div class="box-body">
            <form method="POST" action="{{ route('exam-papers.upload-for-student', $examPaper) }}" enctype="multipart/form-data" class="row">@csrf
                <div class="col-md-4"><select name="student_id" class="form-control" required><option value="">{{ __('messages.choose_student') }}</option>@foreach($students as $assigned)<option value="{{ $assigned->student_id }}">{{ $assigned->student->name }} (ID: {{ $assigned->student_id }})</option>@endforeach</select></div>
                <div class="col-md-5"><input type="file" name="answer_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required></div>
                <div class="col-md-3"><button class="btn btn-primary">{{ __('messages.upload_answer_sheet') }}</button></div>
            </form>
        </div></div>
        <div class="box"><div class="box-header"><h4>{{ __('messages.review_exam') }}</h4></div><div class="box-body table-responsive">
            <table class="table table-bordered"><thead><tr><th>{{ __('messages.student') }}</th><th>{{ __('messages.ai_marks') }}</th><th>{{ __('messages.final_marks') }}</th><th>{{ __('messages.feedback') }}</th><th></th></tr></thead><tbody>
            @forelse($examPaper->submissions as $item)<tr>
                <td>{{ $item->student->name }}<br><small>ID: {{ $item->student->id }}</small></td><td>{{ $item->ai_marks ?? '—' }}<br><form method="POST" action="{{ route('exam-papers.regrade', [$examPaper, $item]) }}" class="mt-5">@csrf<button class="btn btn-xs btn-info">{{ __('messages.regrade') }}</button></form></td>
                <td><form method="POST" action="{{ route('exam-papers.review', [$examPaper, $item]) }}" class="form-inline">@csrf<input name="final_marks" type="number" min="0" max="{{ $examPaper->max_marks }}" step="0.01" class="form-control mr-5" value="{{ $item->final_marks ?? $item->ai_marks }}" required><input name="ai_feedback" class="form-control mr-5" value="{{ $item->ai_feedback }}"><button class="btn btn-success">{{ __('messages.save_feedback') }}</button></form></td>
                <td><a href="{{ Storage::disk('public')->url($item->answer_file) }}" target="_blank">{{ __('messages.answer_file') }}</a></td>
                <td>{{ $item->reviewed_at ? $item->reviewed_at->format('d M Y H:i') : '—' }}</td>
            </tr>@empty<tr><td colspan="5">{{ __('messages.no_submissions') }}</td></tr>@endforelse
            </tbody></table>
        </div></div>
    @endif
</section></div></div>
@endsection
