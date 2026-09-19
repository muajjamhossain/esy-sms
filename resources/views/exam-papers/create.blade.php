@extends('admin.admin_master')
@section('admin')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h3 class="box-title">{{ __('messages.create_exam_paper') }}</h3>
                    <a href="{{ route('exam-papers.index') }}" class="btn btn-rounded btn-outline btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> {{ __('messages.all_classes') }} / {{ __('messages.exam_papers') }}
                    </a>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('exam-papers.store') }}" enctype="multipart/form-data" id="exam-paper-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">{{ __('messages.title') }} <span class="text-danger">*</span></label>
                                <input name="title" class="form-control" placeholder="যেমন: গণিত ১ম পত্র - এমসিকিউ পরীক্ষা" required value="{{ old('title') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="font-weight-bold">{{ __('messages.max_marks') }} <span class="text-danger">*</span></label>
                                <input name="max_marks" type="number" min="0.01" step="0.01" class="form-control" placeholder="যেমন: 20" required value="{{ old('max_marks', 20) }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="font-weight-bold">{{ __('messages.student_year') }} <span class="text-danger">*</span></label>
                                <select name="year_id" class="form-control" required>
                                    <option value="">— {{ __('messages.student_year') }} —</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="font-weight-bold">{{ __('messages.class') }} <span class="text-danger">*</span></label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">— {{ __('messages.class') }} —</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="font-weight-bold">{{ __('messages.subject') }} <span class="text-danger">*</span></label>
                                <select name="subject_id" class="form-control" required>
                                    <option value="">— {{ __('messages.subject') }} —</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="font-weight-bold">{{ __('messages.exam_type') }} <span class="text-danger">*</span></label>
                                <select name="exam_type_id" class="form-control" required>
                                    <option value="">— {{ __('messages.exam_type') }} —</option>
                                    @foreach($examTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('exam_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="font-weight-bold">{{ __('messages.question_type') }} <span class="text-danger">*</span></label>
                                <select id="question-type" class="form-control">
                                    <option value="mcq" selected>🌟 {{ __('messages.mcq_questions') }} (ভয়েস টাইপিং)</option>
                                    <option value="upload">📄 {{ __('messages.upload_question_file') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Upload Section for non-MCQ -->
                        <div id="upload-section" class="row" style="display:none;">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">{{ __('messages.question_file') }}</label>
                                <input type="file" name="question_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">{{ __('messages.answer_key_file') }}</label>
                                <input type="file" name="answer_key_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <!-- MCQ Section -->
                        <div id="mcq-section" class="mt-20">
                            <!-- Voice Typing Info Banner -->
                            <div class="alert alert-primary bg-primary text-white d-flex align-items-center justify-content-between p-15 mb-20" style="border-radius: 8px;">
                                <div>
                                    <h5 class="mb-5 text-white"><i class="fa fa-microphone mr-10"></i> <strong>গুগল ভয়েস টাইপিং (Google Voice Typing)</strong></h5>
                                    <p class="mb-0 text-white" style="opacity: 0.95;">
                                        প্রশ্ন এবং প্রতিটি অপশনে (A, B, C, D) ভয়েস বাটনে ক্লিক করে বাংলা অথবা ইংরেজিতে সরাসরি কথা বলে প্রশ্ন টাইপ করতে পারবেন।
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-light px-10 py-5 text-dark font-weight-bold">
                                        <i class="fa fa-language mr-5"></i> বাংলা (bn-BD) ও English (en-US)
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-15">
                                <h4 class="mb-0 text-dark font-weight-bold">
                                    <i class="fa fa-list-ol mr-5 text-primary"></i> {{ __('messages.mcq_questions') }}
                                    <span id="question-badge" class="badge badge-info ml-10">1 {{ __('messages.questions') }}</span>
                                </h4>
                                <button type="button" id="add-question" class="btn btn-success btn-sm btn-rounded shadow-sm">
                                    <i class="fa fa-plus-circle mr-5"></i> {{ __('messages.add_question') }}
                                </button>
                            </div>

                            <div id="mcq-question-container"></div>
                            <input type="hidden" name="mcq_questions" id="mcq_questions" value="[]">
                        </div>

                        <div class="mt-30 pt-15 border-top">
                            <button type="submit" class="btn btn-primary btn-lg px-30 shadow">
                                <i class="fa fa-check-circle mr-5"></i> {{ __('messages.create_exam_paper') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
.mcq-card {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
    background: #ffffff;
}
.mcq-card:hover {
    border-color: #cbd5e1;
}
.voice-pulse {
    animation: voicePulsing 1.5s infinite;
    background-color: #ef4444 !important;
    border-color: #ef4444 !important;
    color: #ffffff !important;
}
@keyframes voicePulsing {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.correct-option-btn {
    cursor: pointer;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.15s ease;
}
.option-box {
    border-radius: 8px;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.option-box.is-correct {
    border-color: #10b981;
    background: #ecfdf5;
}
</style>

<script>
    let questionCounter = 0;
    let activeRecognition = null;
    let activeRecordingBtn = null;

    function buildQuestionCard(index) {
        const cardId = 'mcq-question-' + index;
        const voiceQuestionId = 'voice-question-' + index;
        const html = `
            <div class="box box-default mcq-question-row mcq-card mb-25" data-index="${index}" id="${cardId}">
                <div class="box-header with-border py-10 px-15 bg-light d-flex justify-content-between align-items-center" style="border-radius: 10px 10px 0 0;">
                    <span class="font-weight-bold text-primary font-size-16">
                        <i class="fa fa-question-circle mr-5"></i> প্রশ্ন #${index + 1}
                    </span>
                    <div class="d-flex align-items-center">
                        <div class="input-group input-group-sm mr-15" style="width: 140px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fa fa-globe"></i></span>
                            </div>
                            <select class="form-control question-language font-size-12">
                                <option value="bn" selected>বাংলা (BN)</option>
                                <option value="en">English (EN)</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-xs remove-question" title="{{ __('messages.remove') }}">
                            <i class="fa fa-trash"></i> {{ __('messages.remove') }}
                        </button>
                    </div>
                </div>
                <div class="box-body p-20">
                    <!-- Question Prompt Input with Voice -->
                    <div class="form-group mb-20">
                        <label class="font-weight-bold text-dark font-size-15 mb-5">
                            {{ __('messages.question') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <textarea class="form-control question-text font-size-15" id="${voiceQuestionId}" rows="2" placeholder="{{ __('messages.type_question_here') }}" required></textarea>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary voice-btn px-15" data-target="${voiceQuestionId}" title="ভয়েস টাইপ করুন (কথা বলুন)">
                                    <i class="fa fa-microphone"></i> <span class="voice-btn-text ml-5">ভয়েস টাইপ</span>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted font-size-12">
                            <i class="fa fa-info-circle mr-3"></i> মাইক্রোফোন বাটনে ক্লিক করে প্রশ্ন মুখে বলুন। বাংলা বা ইংরেজিতে টাইপ হবে।
                        </small>
                    </div>

                    <!-- 4 Options with Individual Voice Inputs -->
                    <label class="font-weight-bold text-dark mb-10">৪টি অপশন লিখুন এবং সঠিক উত্তর চিহ্নিত করুন <span class="text-danger">*</span></label>
                    <div class="row">
                        ${[0, 1, 2, 3].map((optIdx) => {
                            const optChar = String.fromCharCode(65 + optIdx);
                            const optInputId = 'opt-input-' + index + '-' + optIdx;
                            return `
                                <div class="col-md-6 mb-15">
                                    <div class="option-box ${optIdx === 0 ? 'is-correct' : ''}" id="opt-box-${index}-${optIdx}">
                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <span class="badge badge-secondary font-size-13 px-8 py-3">অপশন ${optChar}</span>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="correct-${index}-${optIdx}" name="correct_radio_${index}" value="${optIdx}" class="custom-control-input correct-radio" ${optIdx === 0 ? 'checked' : ''} data-qindex="${index}">
                                                <label class="custom-control-label font-weight-bold text-success cursor-pointer" for="correct-${index}-${optIdx}">
                                                    <i class="fa fa-check-circle"></i> সঠিক উত্তর
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control question-option font-size-14" id="${optInputId}" data-index="${optIdx}" placeholder="অপশন ${optChar} এর উত্তর লিখুন বা বলুন..." required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary voice-btn" data-target="${optInputId}" title="অপশন মুখে বলুন">
                                                    <i class="fa fa-microphone"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>

                    <input type="hidden" class="correct-option-val" value="0">
                </div>
            </div>
        `;
        return html;
    }

    function syncMcqQuestions() {
        const rows = document.querySelectorAll('.mcq-question-row');
        const payload = [];
        rows.forEach((row) => {
            const question = row.querySelector('.question-text').value.trim();
            const language = row.querySelector('.question-language').value;
            const options = Array.from(row.querySelectorAll('.question-option')).map((input) => input.value.trim());
            const correctOption = Number(row.querySelector('.correct-option-val').value);

            if (question !== '' || options.some((o) => o !== '')) {
                payload.push({
                    question: question,
                    language: language,
                    options: options,
                    correct_option: correctOption,
                });
            }
        });

        document.getElementById('mcq_questions').value = JSON.stringify(payload);
        const badge = document.getElementById('question-badge');
        if (badge) {
            badge.innerText = rows.length + ' {{ __('messages.questions') }}';
        }
    }

    function addQuestionCard() {
        const container = document.getElementById('mcq-question-container');
        container.insertAdjacentHTML('beforeend', buildQuestionCard(questionCounter));
        questionCounter++;
        syncMcqQuestions();
    }

    // Toggle between Upload and MCQ
    document.getElementById('question-type').addEventListener('change', function () {
        const isMcq = this.value === 'mcq';
        document.getElementById('mcq-section').style.display = isMcq ? 'block' : 'none';
        document.getElementById('upload-section').style.display = isMcq ? 'none' : 'block';
        const questionFile = document.querySelector('input[name="question_file"]');
        if (questionFile) {
            questionFile.required = !isMcq;
        }
    });

    // Handle correct answer radio change
    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('correct-radio')) {
            const qIndex = event.target.getAttribute('data-qindex');
            const selectedVal = Number(event.target.value);
            const card = document.getElementById('mcq-question-' + qIndex);
            if (card) {
                card.querySelector('.correct-option-val').value = selectedVal;
                // update visual borders
                [0, 1, 2, 3].forEach((idx) => {
                    const optBox = document.getElementById(`opt-box-${qIndex}-${idx}`);
                    if (optBox) {
                        if (idx === selectedVal) {
                            optBox.classList.add('is-correct');
                        } else {
                            optBox.classList.remove('is-correct');
                        }
                    }
                });
            }
            syncMcqQuestions();
        }
    });

    // Speech Recognition Handler (Web Speech API / Google Speech)
    document.addEventListener('click', function (event) {
        const button = event.target.closest('.voice-btn');
        if (!button) return;

        // If clicking an already active recording button, stop it
        if (activeRecognition && activeRecordingBtn === button) {
            activeRecognition.stop();
            return;
        }

        // If another recognition was running, stop it first
        if (activeRecognition) {
            activeRecognition.stop();
        }

        const targetId = button.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);
        if (!targetInput) return;

        // Determine language from parent question row
        const row = button.closest('.mcq-question-row');
        const langSelect = row ? row.querySelector('.question-language') : null;
        const selectedLang = langSelect ? langSelect.value : 'bn';
        const speechLang = selectedLang === 'bn' ? 'bn-BD' : 'en-US';

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            alert('{{ __('messages.voice_not_supported') }} \nদয়া করে Google Chrome বা Microsoft Edge ব্রাউজার ব্যবহার করুন।');
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.lang = speechLang;
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;
        recognition.continuous = false;

        const originalBtnHtml = button.innerHTML;
        button.classList.add('voice-pulse');
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> <span class="voice-btn-text ml-5">{{ __('messages.listening') }}</span>';
        activeRecognition = recognition;
        activeRecordingBtn = button;

        recognition.onresult = function (e) {
            let transcript = '';
            for (let i = 0; i < e.results.length; i++) {
                transcript += e.results[i][0].transcript;
            }
            transcript = transcript.trim();
            if (transcript) {
                // If input already has text, append with space, else set
                const existing = targetInput.value.trim();
                targetInput.value = existing ? existing + ' ' + transcript : transcript;
                targetInput.dispatchEvent(new Event('input'));
                syncMcqQuestions();
            }
        };

        recognition.onerror = function (e) {
            console.warn('Speech recognition error:', e.error);
            if (e.error === 'not-allowed') {
                alert('ব্রাউজারে মাইক্রোফোন ব্যবহারের অনুমতি দেওয়া হয়নি। অনুগ্রহ করে মাইক্রোফোন পারমিশন অন করুন।');
            }
        };

        recognition.onend = function () {
            button.classList.remove('voice-pulse');
            button.innerHTML = originalBtnHtml;
            activeRecognition = null;
            activeRecordingBtn = null;
            syncMcqQuestions();
        };

        try {
            recognition.start();
        } catch (err) {
            button.classList.remove('voice-pulse');
            button.innerHTML = originalBtnHtml;
            activeRecognition = null;
            activeRecordingBtn = null;
        }
    });

    // Remove Question
    document.addEventListener('click', function (event) {
        if (event.target.closest('.remove-question')) {
            const rows = document.querySelectorAll('.mcq-question-row');
            if (rows.length <= 1) {
                alert('কমপক্ষে একটি প্রশ্ন থাকা আবশ্যক।');
                return;
            }
            event.target.closest('.mcq-question-row').remove();
            syncMcqQuestions();
        }
    });

    document.getElementById('add-question').addEventListener('click', addQuestionCard);

    document.addEventListener('input', function () {
        syncMcqQuestions();
    });

    // Form submission validation
    document.getElementById('exam-paper-form').addEventListener('submit', function (e) {
        syncMcqQuestions();
        const questionType = document.getElementById('question-type').value;
        if (questionType === 'mcq') {
            const questionsJson = document.getElementById('mcq_questions').value;
            let questions = [];
            try {
                questions = JSON.parse(questionsJson);
            } catch (err) {}

            if (!questions || questions.length === 0) {
                e.preventDefault();
                alert('অনুগ্রহ করে কমপক্ষে একটি এমসিকিউ প্রশ্ন যোগ করুন।');
                return false;
            }

            for (let i = 0; i < questions.length; i++) {
                if (!questions[i].question || questions[i].question.trim() === '') {
                    e.preventDefault();
                    alert(`প্রশ্ন #${i + 1} এর টেক্সট খালি রয়েছে। অনুগ্রহ করে প্রশ্ন লিখুন।`);
                    return false;
                }
                for (let j = 0; j < 4; j++) {
                    if (!questions[i].options[j] || questions[i].options[j].trim() === '') {
                        e.preventDefault();
                        alert(`প্রশ্ন #${i + 1} এর অপশন ${String.fromCharCode(65 + j)} খালি রয়েছে। ৪টি অপশনই পূরণ করতে হবে।`);
                        return false;
                    }
                }
            }
        }
    });

    // Initialize with 1 default question card
    addQuestionCard();
</script>
@endsection
