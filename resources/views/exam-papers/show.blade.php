@extends('admin.admin_master')
@section('admin')
<div class="content-wrapper"><div class="container-full"><section class="content">
    <div class="box"><div class="box-header"><h3>{{ $examPaper->title }}</h3><p>{{ $examPaper->year->name ?? '' }} · {{ $examPaper->studentClass->name ?? __('messages.all_classes') }} · {{ $examPaper->subject->name ?? __('messages.general') }} · {{ __('messages.max_marks') }}: {{ $examPaper->max_marks }}</p></div><div class="box-body">
        @if($examPaper->isMcq())
            <div class="alert alert-info">{{ __('messages.mcq_questions') }}</div>
            @foreach($examPaper->questions as $index => $question)
                <div class="border p-15 mb-15">
                    <p><strong>{{ $index + 1 }}.</strong> {{ $question['question'] ?? '' }}</p>
                    @foreach(($question['options'] ?? []) as $optionIndex => $option)
                        <div>{{ chr(65 + $optionIndex) }}. {{ $option }}</div>
                    @endforeach
                </div>
            @endforeach
        @else
            <p><a href="{{ asset('storage/' . ltrim($examPaper->question_file, '/')) }}" target="_blank">{{ __('messages.question_file') }}</a></p>
            @if($examPaper->answer_key_file)<p><a href="{{ asset('storage/' . ltrim($examPaper->answer_key_file, '/')) }}" target="_blank">{{ __('messages.answer_key_file') }}</a></p>@endif
        @endif
    </div></div>

    @if(strtolower((string) (auth()->user()->role ?: auth()->user()->usertype)) === 'student')
        <div class="box"><div class="box-header"><h4>{{ __('messages.submit_answer_sheet') }}</h4></div><div class="box-body">
            @if($examPaper->isMcq())
                @if($examPaper->is_published && $submission)
                    <div class="alert alert-success">{{ __('messages.final_marks') }}: {{ $submission->final_marks ?? 0 }}/{{ $examPaper->max_marks }}</div>
                @elseif($submission && $submission->final_marks !== null)
                    <div class="alert alert-warning">{{ __('messages.result_hidden_until_published') }}</div>
                @endif

                <form method="POST" action="{{ route('exam-papers.submit', $examPaper) }}">@csrf
                    @foreach($examPaper->questions as $index => $question)
                        <div class="border p-15 mb-15">
                            <p><strong>{{ $index + 1 }}.</strong> {{ $question['question'] ?? '' }}</p>
                            @foreach(($question['options'] ?? []) as $optionIndex => $option)
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="answers[{{ $index }}]" value="{{ $optionIndex }}" {{ (isset($submission->answers[$index]) && (int) $submission->answers[$index] === (int) $optionIndex) ? 'checked' : '' }} required>
                                        {{ chr(65 + $optionIndex) }}. {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <button class="btn btn-primary">{{ __('messages.submit_answer_sheet') }}</button>
                </form>
            @else
                @if($submission && $submission->final_marks !== null && $examPaper->is_published)<div class="alert alert-success">{{ __('messages.final_marks') }}: {{ $submission->final_marks }}/{{ $examPaper->max_marks }}</div>@endif
                <form method="POST" action="{{ route('exam-papers.submit', $examPaper) }}" enctype="multipart/form-data">@csrf
                    <input type="file" name="answer_file" class="form-control mb-15" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    <button class="btn btn-primary">{{ __('messages.submit_answer_sheet') }}</button>
                </form>
            @endif
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
            @if($examPaper->isMcq())
                @if(! $examPaper->is_published)
                    <form method="POST" action="{{ route('exam-papers.publish', $examPaper) }}">@csrf<button class="btn btn-success mb-20">{{ __('messages.publish_results') }}</button></form>
                @else
                    <div class="alert alert-success mb-20">{{ __('messages.results_published') }}</div>
                @endif
                <table class="table table-bordered"><thead><tr><th>{{ __('messages.student') }}</th><th>{{ __('messages.selected_answers') }}</th><th>{{ __('messages.final_marks') }}</th><th>{{ __('messages.status') }}</th></tr></thead><tbody>
                @forelse($examPaper->submissions as $item)
                    @php $answerSummary = []; @endphp
                    @if(! empty($item->answers) && is_array($item->answers))
                        @foreach($item->answers as $answerIndex => $answerValue)
                            @php $answerSummary[] = ($answerIndex + 1).'. '.chr(65 + (int) $answerValue); @endphp
                        @endforeach
                    @endif
                    <tr>
                        <td>{{ $item->student->name }}</td>
                        <td>{{ implode(', ', $answerSummary) ?: '—' }}</td>
                        <td>{{ $item->final_marks !== null ? $item->final_marks.' / '.$examPaper->max_marks : '—' }}</td>
                        <td>{{ $examPaper->is_published ? __('messages.published') : __('messages.pending') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">{{ __('messages.no_submissions') }}</td></tr>
                @endforelse
                </tbody></table>
            @else
                <table class="table table-bordered"><thead><tr><th>{{ __('messages.student') }}</th><th>{{ __('messages.ai_marks') }}</th><th>{{ __('messages.final_marks') }}</th><th>{{ __('messages.feedback') }}</th><th></th></tr></thead><tbody>
                @forelse($examPaper->submissions as $item)<tr>
                    <td>{{ $item->student->name }}<br><small>ID: {{ $item->student->id }}</small></td><td>{{ $item->ai_marks ?? '—' }}<br><form method="POST" action="{{ route('exam-papers.regrade', [$examPaper, $item]) }}" class="mt-5">@csrf<button class="btn btn-xs btn-info">{{ __('messages.regrade') }}</button></form></td>
                    <td><form method="POST" action="{{ route('exam-papers.review', [$examPaper, $item]) }}" class="form-inline">@csrf<input name="final_marks" type="number" min="0" max="{{ $examPaper->max_marks }}" step="0.01" class="form-control mr-5" value="{{ $item->final_marks ?? $item->ai_marks }}" required><input name="ai_feedback" class="form-control mr-5" value="{{ $item->ai_feedback }}"><button class="btn btn-success">{{ __('messages.save_feedback') }}</button></form></td>
                    <td><a href="{{ asset('storage/' . ltrim($item->answer_file, '/')) }}" target="_blank">{{ __('messages.answer_file') }}</a></td>
                    <td>{{ $item->reviewed_at ? $item->reviewed_at->format('d M Y H:i') : '—' }}</td>
                </tr>@empty<tr><td colspan="5">{{ __('messages.no_submissions') }}</td></tr>@endforelse
                </tbody></table>
            @endif
        </div></div>
    @endif

    @if(strtolower((string) (auth()->user()->role ?: auth()->user()->usertype)) === 'student' && $examPaper->is_published && $submission)
        <div class="box"><div class="box-body"><button class="btn btn-default" onclick="window.print(); return false;">{{ __('messages.print_result') }}</button></div></div>
    @endif
</section></div></div>
@endsection
