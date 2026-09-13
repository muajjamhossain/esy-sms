@extends('admin.admin_master')
@section('admin')
<div class="content-wrapper"><div class="container-full"><section class="content"><div class="box"><div class="box-header"><h3>{{ __('messages.create_exam_paper') }}</h3></div><div class="box-body">
    <form method="POST" action="{{ route('exam-papers.store') }}" enctype="multipart/form-data" id="exam-paper-form">@csrf
        <div class="row">
            <div class="col-md-6 form-group"><label>{{ __('messages.title') }}</label><input name="title" class="form-control" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.max_marks') }}</label><input name="max_marks" type="number" min="0.01" step="0.01" class="form-control" required></div>
            <div class="col-md-3 form-group"><label>{{ __('messages.class') }}</label><select name="class_id" class="form-control"><option value="">—</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.subject') }}</label><select name="subject_id" class="form-control"><option value="">—</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.exam_type') }}</label><select name="exam_type_id" class="form-control"><option value="">—</option>@foreach($examTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>{{ __('messages.question_type') }}</label><select id="question-type" class="form-control"><option value="upload">{{ __('messages.upload_question_file') }}</option><option value="mcq">{{ __('messages.mcq_questions') }}</option></select></div>
        </div>

        <div id="upload-section" class="row">
            <div class="col-md-6 form-group"><label>{{ __('messages.question_file') }}</label><input type="file" name="question_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></div>
            <div class="col-md-6 form-group"><label>{{ __('messages.answer_key_file') }}</label><input type="file" name="answer_key_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></div>
        </div>

        <div id="mcq-section" class="mt-20" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-15">
                <h4>{{ __('messages.mcq_questions') }}</h4>
                <button type="button" id="add-question" class="btn btn-info btn-sm">{{ __('messages.add_question') }}</button>
            </div>
            <div id="mcq-question-container"></div>
            <input type="hidden" name="mcq_questions" id="mcq_questions" value="[]">
        </div>

        <button class="btn btn-success mt-20">{{ __('messages.create_exam_paper') }}</button>
    </form>
</div></div></section></div></div>

<script>
    let questionCounter = 0;

    function buildQuestionCard(index) {
        const cardId = 'mcq-question-' + index;
        const voiceId = 'voice-question-' + index;
        const html = `
            <div class="box box-default mcq-question-row mb-20" data-index="${index}" id="${cardId}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label>{{ __('messages.question') }}</label>
                            <textarea class="form-control question-text" id="${voiceId}" rows="3" placeholder="{{ __('messages.type_question_here') }}"></textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ __('messages.question_language') }}</label>
                            <select class="form-control question-language">
                                <option value="bn">{{ __('messages.bangla') }}</option>
                                <option value="en">{{ __('messages.english') }}</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-primary voice-button mt-10" data-target="${voiceId}" data-language="bn">{{ __('messages.voice_input') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        ${[0,1,2,3].map((optionIndex) => `
                            <div class="col-md-6 form-group">
                                <label>{{ __('messages.option') }} ${String.fromCharCode(65 + optionIndex)}</label>
                                <input type="text" class="form-control question-option" data-index="${optionIndex}" placeholder="{{ __('messages.option') }} ${String.fromCharCode(65 + optionIndex)}">
                            </div>
                        `).join('')}
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>{{ __('messages.correct_option') }}</label>
                            <select class="form-control correct-option">
                                <option value="0">A</option>
                                <option value="1">B</option>
                                <option value="2">C</option>
                                <option value="3">D</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group text-right">
                            <button type="button" class="btn btn-danger remove-question mt-25">{{ __('messages.remove') }}</button>
                        </div>
                    </div>
                </div>
            </div>`;
        return html;
    }

    function syncMcqQuestions() {
        const rows = document.querySelectorAll('.mcq-question-row');
        const payload = [];
        rows.forEach((row) => {
            const question = row.querySelector('.question-text').value.trim();
            const language = row.querySelector('.question-language').value;
            const options = Array.from(row.querySelectorAll('.question-option')).map((input) => input.value.trim());
            const correctOption = Number(row.querySelector('.correct-option').value);
            if (question || options.some(Boolean)) {
                payload.push({
                    question,
                    language,
                    options,
                    correct_option: correctOption,
                });
            }
        });
        document.getElementById('mcq_questions').value = JSON.stringify(payload);
    }

    function addQuestionCard() {
        const container = document.getElementById('mcq-question-container');
        container.insertAdjacentHTML('beforeend', buildQuestionCard(questionCounter));
        const button = document.querySelectorAll('.voice-button')[document.querySelectorAll('.voice-button').length - 1];
        const languageSelect = container.querySelectorAll('.question-language')[container.querySelectorAll('.question-language').length - 1];
        if (button && languageSelect) {
            button.setAttribute('data-language', languageSelect.value);
            languageSelect.addEventListener('change', function () {
                button.setAttribute('data-language', this.value === 'bn' ? 'bn-BD' : 'en-US');
            });
        }
        questionCounter += 1;
        syncMcqQuestions();
    }

    document.getElementById('add-question').addEventListener('click', addQuestionCard);
    document.getElementById('question-type').addEventListener('change', function () {
        const isMcq = this.value === 'mcq';
        document.getElementById('mcq-section').style.display = isMcq ? 'block' : 'none';
        document.getElementById('upload-section').style.display = isMcq ? 'none' : 'block';
        const questionFile = document.querySelector('input[name="question_file"]');
        if (questionFile) {
            questionFile.required = !isMcq;
        }
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.voice-button');
        if (!button) return;

        const targetId = button.getAttribute('data-target');
        const language = button.getAttribute('data-language') || 'en-US';
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            alert('{{ __('messages.voice_not_supported') }}');
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.lang = language;
        recognition.onresult = function (event) {
            let transcript = '';
            for (let i = 0; i < event.results.length; i++) {
                transcript += event.results[i][0].transcript;
            }
            const target = document.getElementById(targetId);
            if (target) {
                target.value = transcript.trim();
                syncMcqQuestions();
            }
        };
        recognition.start();
    });

    document.addEventListener('input', function () {
        syncMcqQuestions();
    });

    document.addEventListener('click', function (event) {
        if (event.target.closest('.remove-question')) {
            event.target.closest('.mcq-question-row').remove();
            syncMcqQuestions();
        }
    });

    document.getElementById('exam-paper-form').addEventListener('submit', function () {
        syncMcqQuestions();
    });

    addQuestionCard();
</script>
@endsection
