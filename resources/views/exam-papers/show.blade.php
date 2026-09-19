@extends('admin.admin_master')
@section('admin')
@php
    $user = auth()->user();
    $isStudent = strtolower((string) ($user->role ?: $user->usertype)) === 'student';
    $stats = $userSubmissionStats ?? ($submission ? $submission->getMcqStats() : null);
@endphp

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

            <!-- Back & Status Header -->
            <div class="d-flex justify-content-between align-items-center mb-20 flex-wrap">
                <div>
                    <h3 class="font-weight-bold text-dark mb-5">
                        <i class="fa fa-graduation-cap text-primary mr-5"></i> {{ $examPaper->title }}
                    </h3>
                    <p class="text-muted mb-0 font-size-14">
                        <span class="badge badge-primary font-size-13 px-8 py-3 mr-5">{{ $examPaper->isMcq() ? 'এমসিকিউ পরীক্ষা (MCQ Exam)' : 'লিখিত/ফাইল পরীক্ষা' }}</span>
                        <span class="mr-15"><i class="fa fa-book mr-3 text-secondary"></i> {{ $examPaper->subject->name ?? __('messages.general') }}</span>
                        <span class="mr-15"><i class="fa fa-users mr-3 text-secondary"></i> {{ $examPaper->studentClass->name ?? __('messages.all_classes') }}</span>
                        <span class="mr-15"><i class="fa fa-calendar mr-3 text-secondary"></i> {{ $examPaper->year->name ?? '' }}</span>
                        <span class="mr-15"><i class="fa fa-star mr-3 text-warning"></i> {{ __('messages.max_marks') }}: <strong>{{ $examPaper->max_marks }}</strong></span>
                        @if($examPaper->duration_minutes)
                            <span class="badge badge-info-light font-size-13 px-8 py-4">
                                <i class="fa fa-clock-o mr-3"></i> {{ $examPaper->duration_minutes }} {{ __('messages.minutes') }}
                            </span>
                        @endif
                    </p>
                </div>
                <div class="mt-10 d-flex align-items-center">
                    <a href="{{ route('exam-papers.index') }}" class="btn btn-outline btn-secondary btn-sm btn-rounded shadow-sm">
                        <i class="fa fa-arrow-left mr-5"></i> পরীক্ষার তালিকা (All Exams)
                    </a>
                    @if(! $isStudent)
                        <a href="{{ route('exam-papers.delete', $examPaper) }}" class="btn btn-outline btn-danger btn-sm btn-rounded shadow-sm ml-8" onclick="return confirm('{{ __('messages.delete_exam_confirm') }}');">
                            <i class="fa fa-trash mr-5"></i> {{ __('messages.remove') }}
                        </a>
                    @endif
                </div>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle mr-5"></i> {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-exclamation-circle mr-5"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <!-- ========================================== -->
            <!-- STUDENT VIEW -->
            <!-- ========================================== -->
            @if($isStudent)
                @if($examPaper->isMcq())

                    <!-- Case 1: Student has submitted, and Results are PUBLISHED -->
                    @if($submission && $examPaper->is_published)
                        <!-- Published Result Showcase -->
                        <div class="box bg-gradient-primary text-white shadow mb-25" style="border-radius: 12px;">
                            <div class="box-body p-25">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <span class="badge badge-light text-primary font-weight-bold px-12 py-5 mb-10">
                                            <i class="fa fa-bullhorn mr-5"></i> ফলাফল প্রকাশিত (Results Published)
                                        </span>
                                        <h2 class="text-white font-weight-bold mb-5">
                                            প্রাপ্ত নম্বর: {{ $stats['marks'] }} / {{ $examPaper->max_marks }}
                                            <span class="font-size-20 ml-10">({{ $stats['percentage'] }}%)</span>
                                        </h2>
                                        <p class="text-white mb-0 font-size-15" style="opacity: 0.95;">
                                            আপনার অনলাইন এমসিকিউ পরীক্ষার ফলাফল নিচে বিস্তারিত দেখুন এবং প্রিন্ট করুন।
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-right mt-15 mt-md-0">
                                        <a href="{{ route('exam-papers.print', $examPaper) }}" target="_blank" class="btn btn-warning btn-lg shadow-sm font-weight-bold px-20">
                                            <i class="fa fa-print mr-5"></i> {{ __('messages.print_result_sheet') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Score Metric Cards -->
                        <div class="row mb-20">
                            <div class="col-md-3 col-6">
                                <div class="box p-20 text-center shadow-sm border-0" style="border-radius: 10px; background: #ecfdf5; border-left: 4px solid #10b981 !important;">
                                    <h3 class="font-weight-bold text-success mb-5">{{ $stats['correct'] }} টি</h3>
                                    <span class="text-dark font-weight-600 font-size-14"><i class="fa fa-check-circle text-success mr-3"></i> সঠিক উত্তর (Correct)</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="box p-20 text-center shadow-sm border-0" style="border-radius: 10px; background: #fef2f2; border-left: 4px solid #ef4444 !important;">
                                    <h3 class="font-weight-bold text-danger mb-5">{{ $stats['wrong'] }} টি</h3>
                                    <span class="text-dark font-weight-600 font-size-14"><i class="fa fa-times-circle text-danger mr-3"></i> ভুল উত্তর (Incorrect)</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="box p-20 text-center shadow-sm border-0" style="border-radius: 10px; background: #fffbeb; border-left: 4px solid #f59e0b !important;">
                                    <h3 class="font-weight-bold text-warning mb-5">{{ $stats['unanswered'] }} টি</h3>
                                    <span class="text-dark font-weight-600 font-size-14"><i class="fa fa-minus-circle text-warning mr-3"></i> অনুত্তরিত (Skipped)</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="box p-20 text-center shadow-sm border-0" style="border-radius: 10px; background: #eff6ff; border-left: 4px solid #3b82f6 !important;">
                                    <h3 class="font-weight-bold text-primary mb-5">{{ $stats['total'] }} টি</h3>
                                    <span class="text-dark font-weight-600 font-size-14"><i class="fa fa-list-ol text-primary mr-3"></i> মোট প্রশ্ন (Total)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Question-by-Question Review with Colored Options -->
                        <div class="box shadow-sm" style="border-radius: 10px;">
                            <div class="box-header with-border">
                                <h4 class="box-title font-weight-bold text-dark">
                                    <i class="fa fa-check-square-o text-primary mr-5"></i> বিস্তারিত প্রশ্ন ও উত্তর পর্যালোচনা (Question Review)
                                </h4>
                            </div>
                            <div class="box-body p-20">
                                @foreach($examPaper->questions as $qIdx => $question)
                                    @php
                                        $userAns = isset($submission->answers[$qIdx]) && $submission->answers[$qIdx] !== '' ? (int) $submission->answers[$qIdx] : null;
                                        $correctAns = (int) ($question['correct_option'] ?? 0);
                                        $isCorrect = ($userAns !== null && $userAns === $correctAns);
                                        $isUnanswered = ($userAns === null);
                                    @endphp

                                    <div class="border rounded p-20 mb-20 bg-white shadow-xs" style="border-color: #e2e8f0;">
                                        <div class="d-flex justify-content-between align-items-start mb-15">
                                            <h5 class="font-weight-bold text-dark mb-0">
                                                <span class="badge badge-secondary mr-5">#{{ $qIdx + 1 }}</span>
                                                {{ $question['question'] ?? '' }}
                                            </h5>
                                            <div>
                                                @if($isCorrect)
                                                    <span class="badge badge-success font-size-13 px-10 py-5">
                                                        <i class="fa fa-check mr-3"></i> সঠিক (Correct)
                                                    </span>
                                                @elseif($isUnanswered)
                                                    <span class="badge badge-warning font-size-13 px-10 py-5">
                                                        <i class="fa fa-minus mr-3"></i> অনুত্তরিত (Skipped)
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger font-size-13 px-10 py-5">
                                                        <i class="fa fa-times mr-3"></i> ভুল (Incorrect)
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            @foreach(($question['options'] ?? []) as $optIdx => $option)
                                                @php
                                                    $isThisCorrect = ($optIdx === $correctAns);
                                                    $isThisSelected = ($optIdx === $userAns);

                                                    $boxClass = 'bg-light border';
                                                    if ($isThisCorrect) {
                                                        $boxClass = 'border-success text-success font-weight-bold';
                                                    } elseif ($isThisSelected && ! $isCorrect) {
                                                        $boxClass = 'border-danger text-danger font-weight-bold';
                                                    }
                                                @endphp
                                                <div class="col-md-6 mb-10">
                                                    <div class="p-10 rounded d-flex justify-content-between align-items-center {{ $boxClass }}" style="background: {{ $isThisCorrect ? '#f0fdf4' : ($isThisSelected ? '#fef2f2' : '#f8fafc') }};">
                                                        <div>
                                                            <span class="badge badge-dark mr-5">{{ chr(65 + $optIdx) }}</span>
                                                            {{ $option }}
                                                        </div>
                                                        <div>
                                                            @if($isThisSelected && $isThisCorrect)
                                                                <span class="badge badge-success font-size-11"><i class="fa fa-check"></i> আপনার উত্তর (সঠিক)</span>
                                                            @elseif($isThisSelected && ! $isThisCorrect)
                                                                <span class="badge badge-danger font-size-11"><i class="fa fa-times"></i> আপনার উত্তর (ভুল)</span>
                                                            @elseif($isThisCorrect)
                                                                <span class="badge badge-success font-size-11"><i class="fa fa-check-circle"></i> সঠিক উত্তর</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    <!-- Case 2: Student has submitted, but Results are NOT PUBLISHED YET -->
                    @elseif($submission && ! $examPaper->is_published)
                        <div class="box shadow-sm border-0" style="border-radius: 12px;">
                            <div class="box-body p-40 text-center">
                                <div class="mb-20">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-warning text-white rounded-circle shadow" style="width: 80px; height: 80px; font-size: 36px;">
                                        <i class="fa fa-hourglass-half"></i>
                                    </span>
                                </div>
                                <h3 class="font-weight-bold text-dark mb-10">উত্তরপত্র সফলভাবে জমা হয়েছে!</h3>
                                <p class="text-muted font-size-16 mb-20 max-w-600 mx-auto" style="line-height: 1.6;">
                                    {{ __('messages.exam_submitted_notice') }}
                                </p>
                                <div class="alert alert-warning d-inline-block px-25 py-10 font-size-14 font-weight-600 mb-0" style="border-radius: 8px;">
                                    <i class="fa fa-lock mr-5"></i> শিক্ষক ফলাফল প্রকাশ করার পর আপনি নম্বর দেখতে পারবেন ও রেজাল্ট শিট প্রিন্ট করতে পারবেন।
                                </div>
                            </div>
                        </div>

                    <!-- Case 3: Student has NOT submitted yet -> Show Exam Taking Room -->
                    @else
                        @if($examPaper->duration_minutes)
                            <!-- Floating Countdown Timer Widget -->
                            <div id="sticky-timer-bar" class="p-15 mb-20 d-flex align-items-center justify-content-between shadow-sm" style="position: sticky; top: 70px; z-index: 1020; background: #ffffff; border-radius: 10px; border-left: 5px solid #3b82f6;">
                                <div class="d-flex align-items-center">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mr-12 shadow-sm" style="width: 42px; height: 42px;">
                                        <i class="fa fa-clock-o font-size-20"></i>
                                    </span>
                                    <div>
                                        <div class="font-weight-bold font-size-12 text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('messages.time_left') }}</div>
                                        <div class="font-weight-bold text-dark font-size-13">সময় শেষ হলে পরীক্ষা স্বয়ংক্রিয়ভাবে জমা হবে</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div id="countdown-display" class="badge badge-primary px-18 py-10 font-size-22 font-weight-bold shadow-sm" style="font-family: 'Courier New', Courier, monospace; letter-spacing: 2px;">
                                        --:--
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="box shadow-sm" style="border-radius: 12px;">
                            <div class="box-header with-border bg-light d-flex justify-content-between align-items-center py-15 px-20">
                                <div>
                                    <h4 class="box-title font-weight-bold text-dark mb-0">
                                        <i class="fa fa-pencil-square-o text-primary mr-5"></i> অনলাইন এমসিকিউ পরীক্ষা (Online MCQ Test)
                                    </h4>
                                    <small class="text-muted">সঠিক উত্তরটি নির্বাচন করে নিচের 'উত্তরপত্র জমা দিন' বাটনে ক্লিক করুন।</small>
                                </div>
                                <div>
                                    <span id="answered-counter" class="badge badge-info font-size-13 px-12 py-6">
                                        ০ / {{ count($examPaper->questions ?? []) }} টি উত্তর সম্পন্ন
                                    </span>
                                </div>
                            </div>
                            <div class="box-body p-25">
                                <form method="POST" action="{{ route('exam-papers.submit', $examPaper) }}" id="student-exam-form">
                                    @csrf
                                    @foreach(($examPaper->questions ?? []) as $index => $question)
                                        <div class="card mb-25 border p-20 shadow-xs question-box" style="border-radius: 10px; border-color: #e2e8f0;">
                                            <div class="d-flex align-items-start mb-15">
                                                <span class="badge badge-primary font-size-14 px-10 py-5 mr-10">#{{ $index + 1 }}</span>
                                                <h5 class="font-weight-bold text-dark mb-0 font-size-16" style="line-height: 1.5;">
                                                    {{ $question['question'] ?? '' }}
                                                </h5>
                                            </div>

                                            <div class="row">
                                                @foreach(($question['options'] ?? []) as $optionIndex => $option)
                                                    <div class="col-md-6 mb-10">
                                                        <label class="d-block option-label p-12 border rounded cursor-pointer mb-0" style="background: #f8fafc; transition: all 0.15s ease;" for="q_{{ $index }}_opt_{{ $optionIndex }}">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="q_{{ $index }}_opt_{{ $optionIndex }}" name="answers[{{ $index }}]" value="{{ $optionIndex }}" class="custom-control-input student-opt-radio" data-qindex="{{ $index }}">
                                                                <span class="custom-control-label font-weight-600 text-dark">
                                                                    <strong>{{ chr(65 + $optionIndex) }}.</strong> {{ $option }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="text-center pt-20 border-top">
                                        <button type="button" id="btn-submit-exam" class="btn btn-success btn-lg px-40 shadow font-weight-bold">
                                            <i class="fa fa-paper-plane mr-5"></i> উত্তরপত্র জমা দিন (Submit Exam)
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const totalQuestions = {{ count($examPaper->questions ?? []) }};
                                const answeredSet = new Set();
                                const counterEl = document.getElementById('answered-counter');

                                document.querySelectorAll('.student-opt-radio').forEach(function(radio) {
                                    radio.addEventListener('change', function() {
                                        answeredSet.add(this.getAttribute('data-qindex'));
                                        if (counterEl) {
                                            counterEl.innerText = `${answeredSet.size} / ${totalQuestions} টি উত্তর সম্পন্ন`;
                                        }
                                        // highlight selected card
                                        const parentCard = this.closest('.question-box');
                                        if (parentCard) {
                                            parentCard.querySelectorAll('.option-label').forEach(el => el.style.borderColor = '#e2e8f0');
                                            this.closest('.option-label').style.borderColor = '#10b981';
                                            this.closest('.option-label').style.background = '#ecfdf5';
                                        }
                                    });
                                });

                                document.getElementById('btn-submit-exam').addEventListener('click', function() {
                                    const answered = answeredSet.size;
                                    let msg = `আপনি ${totalQuestions} টির মধ্যে ${answered} টি প্রশ্নের উত্তর দিয়েছেন। আপনি কি নিশ্চিত যে উত্তরপত্র জমা দিতে চান? জমা দেওয়ার পর আর পরিবর্তন করা যাবে না।`;
                                    if (answered < totalQuestions) {
                                        msg = `সতর্কতা: ${totalQuestions - answered} টি প্রশ্নের উত্তর এখনো দেননি!\n\n` + msg;
                                    }

                                    if (confirm(msg)) {
                                        document.getElementById('student-exam-form').submit();
                                    }
                                });

                                @if($examPaper->duration_minutes)
                                // Live Countdown Timer
                                const durationMinutes = {{ (int) $examPaper->duration_minutes }};
                                let remainingSeconds = durationMinutes * 60;
                                const timerDisplay = document.getElementById('countdown-display');
                                const timerBar = document.getElementById('sticky-timer-bar');

                                function updateTimerDisplay() {
                                    const hours = Math.floor(remainingSeconds / 3600);
                                    const minutes = Math.floor((remainingSeconds % 3600) / 60);
                                    const seconds = remainingSeconds % 60;

                                    let timeStr = '';
                                    if (hours > 0) {
                                        timeStr += (hours < 10 ? '0' : '') + hours + ':';
                                    }
                                    timeStr += (minutes < 10 ? '0' : '') + minutes + ':';
                                    timeStr += (seconds < 10 ? '0' : '') + seconds;

                                    if (timerDisplay) {
                                        timerDisplay.innerText = timeStr;
                                    }

                                    if (remainingSeconds <= 180 && remainingSeconds > 60) {
                                        if (timerDisplay) timerDisplay.className = 'badge badge-warning text-dark px-18 py-10 font-size-22 font-weight-bold shadow-sm';
                                        if (timerBar) timerBar.style.borderLeftColor = '#f59e0b';
                                    } else if (remainingSeconds <= 60) {
                                        if (timerDisplay) timerDisplay.className = 'badge badge-danger px-18 py-10 font-size-22 font-weight-bold shadow-sm';
                                        if (timerBar) timerBar.style.borderLeftColor = '#ef4444';
                                    }

                                    if (remainingSeconds <= 0) {
                                        clearInterval(timerInterval);
                                        if (timerDisplay) timerDisplay.innerText = '00:00';
                                        alert('{{ __("messages.time_expired") }}\n\n{{ __("messages.auto_submitting") }}');
                                        document.getElementById('student-exam-form').submit();
                                    } else {
                                        remainingSeconds--;
                                    }
                                }

                                updateTimerDisplay();
                                const timerInterval = setInterval(updateTimerDisplay, 1000);
                                @endif
                            });
                        </script>
                    @endif

                @else
                    <!-- Non-MCQ file based exam submission for student -->
                    <div class="box shadow-sm" style="border-radius: 12px;">
                        <div class="box-header with-border">
                            <h4>{{ __('messages.submit_answer_sheet') }}</h4>
                        </div>
                        <div class="box-body p-20">
                            @if($submission && $submission->final_marks !== null && $examPaper->is_published)
                                <div class="alert alert-success font-size-16">
                                    <i class="fa fa-check-circle mr-5"></i> {{ __('messages.final_marks') }}: <strong>{{ $submission->final_marks }} / {{ $examPaper->max_marks }}</strong>
                                </div>
                            @elseif($submission)
                                <div class="alert alert-info">
                                    <i class="fa fa-clock-o mr-5"></i> আপনার ফাইল জমা হয়েছে। শিক্ষক মূল্যায়ন শেষ করলে আপনি ফলাফল দেখতে পারবেন।
                                </div>
                            @endif

                            <p><a href="{{ asset('storage/' . ltrim($examPaper->question_file, '/')) }}" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> {{ __('messages.question_file') }}</a></p>

                            @if(! $submission || ! $examPaper->is_published)
                                <form method="POST" action="{{ route('exam-papers.submit', $examPaper) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label>{{ __('messages.answer_file') }} (PDF/Image)</label>
                                        <input type="file" name="answer_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                    </div>
                                    <button class="btn btn-primary">{{ __('messages.submit_answer_sheet') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

            <!-- ========================================== -->
            <!-- TEACHER / ADMIN VIEW -->
            <!-- ========================================== -->
            @else
                <!-- Teacher Action Bar -->
                <div class="box shadow-sm mb-25" style="border-radius: 10px;">
                    <div class="box-body p-20 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <span class="font-size-15 font-weight-bold text-dark mr-10">ফলাফলের অবস্থা (Status):</span>
                            @if($examPaper->is_published)
                                <span class="badge badge-success font-size-14 px-12 py-6">
                                    <i class="fa fa-check-circle mr-3"></i> ফলাফল প্রকাশিত (Published)
                                </span>
                                <small class="text-muted ml-5">({{ $examPaper->published_at ? $examPaper->published_at->format('d M Y, h:i A') : '' }})</small>
                            @else
                                <span class="badge badge-warning font-size-14 px-12 py-6 text-dark">
                                    <i class="fa fa-clock-o mr-3"></i> অপ্রকাশিত / খসড়া (Unpublished / Draft)
                                </span>
                                <small class="text-muted ml-5">(শিক্ষার্থীরা এখনো ফলাফল দেখতে পাচ্ছে না)</small>
                            @endif
                        </div>

                        <div class="mt-10 mt-md-0 d-flex align-items-center">
                            @if(! $examPaper->is_published)
                                <form method="POST" action="{{ route('exam-papers.publish', $examPaper) }}" onsubmit="return confirm('{{ __('messages.publish_results_confirm') }}');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-rounded shadow-sm px-20 font-weight-bold">
                                        <i class="fa fa-bullhorn mr-5"></i> {{ __('messages.publish_results') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('exam-papers.unpublish', $examPaper) }}" onsubmit="return confirm('আপনি কি ফলাফল প্রত্যাহার করতে চান?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-rounded btn-sm px-15">
                                        <i class="fa fa-eye-slash mr-5"></i> {{ __('messages.unpublish_results') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if($examPaper->isMcq())
                    <!-- Questions preview card for teacher -->
                    <div class="box box-solid collapsed-box mb-25 shadow-sm" style="border-radius: 10px;">
                        <div class="box-header with-border">
                            <h4 class="box-title font-weight-bold text-dark">
                                <i class="fa fa-list-alt text-primary mr-5"></i> প্রশ্নমালা ও সঠিক উত্তর কী (Questions & Answer Key - {{ count($examPaper->questions ?? []) }} টি প্রশ্ন)
                            </h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body p-20" style="display: none;">
                            @foreach(($examPaper->questions ?? []) as $index => $question)
                                <div class="border rounded p-15 mb-15 bg-light">
                                    <p class="font-weight-bold mb-5"><strong>{{ $index + 1 }}.</strong> {{ $question['question'] ?? '' }}</p>
                                    <div class="row">
                                        @foreach(($question['options'] ?? []) as $optionIndex => $option)
                                            @php $isCorrect = ((int) ($question['correct_option'] ?? 0) === $optionIndex); @endphp
                                            <div class="col-md-6 mb-5">
                                                <span class="p-5 px-10 rounded d-inline-block {{ $isCorrect ? 'bg-success text-white font-weight-bold' : 'text-dark' }}">
                                                    {{ chr(65 + $optionIndex) }}. {{ $option }}
                                                    @if($isCorrect) <i class="fa fa-check-circle ml-5"></i> (সঠিক উত্তর) @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submissions & Grading Analytics Table -->
                    <div class="box shadow-sm" style="border-radius: 10px;">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title font-weight-bold text-dark mb-0">
                                <i class="fa fa-users text-primary mr-5"></i> শিক্ষার্থীদের উত্তরপত্র ও ফলাফল (Student Submissions - {{ $examPaper->submissions->count() }} জন)
                            </h4>
                        </div>
                        <div class="box-body p-0 table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 22%;">{{ __('messages.student') }}</th>
                                        <th class="text-center" style="width: 10%;">{{ __('messages.merit_rank') }}</th>
                                        <th>জমার সময় (Submitted At)</th>
                                        <th class="text-center">{{ __('messages.correct') }}</th>
                                        <th class="text-center">{{ __('messages.wrong') }}</th>
                                        <th class="text-center">{{ __('messages.score_obtained') }}</th>
                                        <th class="text-center">{{ __('messages.percentage') }}</th>
                                        <th class="text-center">{{ __('messages.status') }}</th>
                                        <th class="text-right">একশন (Action)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($examPaper->submissions as $sub)
                                        @php
                                            $subStats = $sub->getMcqStats();
                                            $rank = $submissionRanks[$sub->student_id] ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $sub->student->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">ID / Roll: {{ $sub->student->id_no ?? $sub->student->id }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if($rank === 1)
                                                    <span class="badge badge-warning text-dark font-weight-bold font-size-12 px-8 py-4 shadow-xs" title="1st Place">🥇 ১ম</span>
                                                @elseif($rank === 2)
                                                    <span class="badge badge-secondary text-dark font-weight-bold font-size-12 px-8 py-4 shadow-xs" title="2nd Place">🥈 ২য়</span>
                                                @elseif($rank === 3)
                                                    <span class="badge text-white font-weight-bold font-size-12 px-8 py-4 shadow-xs" style="background:#b45309;" title="3rd Place">🥉 ৩য়</span>
                                                @elseif($rank)
                                                    <span class="badge badge-light text-dark font-weight-bold font-size-12 px-8 py-4">#{{ $rank }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $sub->created_at ? $sub->created_at->format('d M Y, h:i A') : '—' }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-success px-8 py-4 font-size-12">{{ $subStats['correct'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-danger px-8 py-4 font-size-12">{{ $subStats['wrong'] }}</span>
                                            </td>
                                            <td class="text-center font-weight-bold font-size-15 text-primary">
                                                {{ $subStats['marks'] }} / {{ $examPaper->max_marks }}
                                            </td>
                                            <td class="text-center font-weight-bold">
                                                {{ $subStats['percentage'] }}%
                                            </td>
                                            <td class="text-center">
                                                @if($examPaper->is_published)
                                                    <span class="badge badge-success px-8 py-3">{{ __('messages.published') }}</span>
                                                @else
                                                    <span class="badge badge-warning px-8 py-3 text-dark">{{ __('messages.pending') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('exam-papers.print', [$examPaper, $sub->student_id]) }}" target="_blank" class="btn btn-sm btn-info btn-rounded shadow-sm">
                                                    <i class="fa fa-print"></i> {{ __('messages.view_print') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-30 text-muted">
                                                <i class="fa fa-folder-open-o font-size-30 d-block mb-10"></i>
                                                {{ __('messages.no_submissions') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                @else
                    <!-- File based manual review for non-MCQ -->
                    <div class="box shadow-sm mb-20">
                        <div class="box-header with-border">
                            <h4>{{ __('messages.upload_for_student') }}</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('exam-papers.upload-for-student', $examPaper) }}" enctype="multipart/form-data" class="row">
                                @csrf
                                <div class="col-md-4">
                                    <select name="student_id" class="form-control" required>
                                        <option value="">{{ __('messages.choose_student') }}</option>
                                        @foreach($students as $assigned)
                                            <option value="{{ $assigned->student_id }}">{{ $assigned->student->name }} (ID: {{ $assigned->student_id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="file" name="answer_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary">{{ __('messages.upload_answer_sheet') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4>{{ __('messages.review_exam') }}</h4>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.student') }}</th>
                                        <th>{{ __('messages.ai_marks') }}</th>
                                        <th>{{ __('messages.final_marks') }}</th>
                                        <th>{{ __('messages.feedback') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($examPaper->submissions as $item)
                                        <tr>
                                            <td>{{ $item->student->name }}<br><small>ID: {{ $item->student->id }}</small></td>
                                            <td>{{ $item->ai_marks ?? '—' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('exam-papers.review', [$examPaper, $item]) }}" class="form-inline">
                                                    @csrf
                                                    <input name="final_marks" type="number" min="0" max="{{ $examPaper->max_marks }}" step="0.01" class="form-control mr-5" value="{{ $item->final_marks ?? $item->ai_marks }}" required>
                                                    <input name="ai_feedback" class="form-control mr-5" value="{{ $item->ai_feedback }}">
                                                    <button class="btn btn-success">{{ __('messages.save_feedback') }}</button>
                                                </form>
                                            </td>
                                            <td><a href="{{ asset('storage/' . ltrim($item->answer_file, '/')) }}" target="_blank">{{ __('messages.answer_file') }}</a></td>
                                            <td>{{ $item->reviewed_at ? $item->reviewed_at->format('d M Y H:i') : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5">{{ __('messages.no_submissions') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

        </section>
    </div>
</div>
@endsection
