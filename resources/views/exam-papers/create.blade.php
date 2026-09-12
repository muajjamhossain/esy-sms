@extends('admin.admin_master')
@section('admin')
<div class="content-wrapper"><div class="container-full"><section class="content"><div class="box"><div class="box-header"><h3>{{ __('messages.create_exam_paper') }}</h3></div><div class="box-body">
    <form method="POST" action="{{ route('exam-papers.store') }}" enctype="multipart/form-data">@csrf
        <div class="row">
            <div class="col-md-6 form-group"><label>{{ __('messages.title') }}</label><input name="title" class="form-control" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.max_marks') }}</label><input name="max_marks" type="number" min="0.01" step="0.01" class="form-control" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.student_year') }}</label><select name="year_id" class="form-control" required><option value="">—</option>@foreach($years as $year)<option value="{{ $year->id }}">{{ $year->name }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.class') }}</label><select name="class_id" class="form-control"><option value="">—</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.subject') }}</label><select name="subject_id" class="form-control"><option value="">—</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.exam_type') }}</label><select name="exam_type_id" class="form-control"><option value="">—</option>@foreach($examTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach</select></div>
            <div class="col-md-6 form-group"><label>{{ __('messages.question_file') }}</label><input type="file" name="question_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required></div>
            <div class="col-md-6 form-group"><label>{{ __('messages.answer_key_file') }}</label><input type="file" name="answer_key_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></div>
        </div>
        <button class="btn btn-success">{{ __('messages.create_exam_paper') }}</button>
    </form>
</div></div></section></div></div>
@endsection
