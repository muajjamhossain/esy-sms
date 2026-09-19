<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.official_result_sheet') }} - {{ $submission->student->name ?? 'Student' }}</title>
    <!-- Fonts & Bootstrap -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .print-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .institute-header {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .institute-header img {
            max-height: 70px;
            margin-bottom: 10px;
        }
        .institute-header h2 {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
            font-size: 26px;
        }
        .institute-header p {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: 14px;
        }
        .info-table th {
            background-color: #f8fafc;
            color: #475569;
            width: 25%;
            font-size: 14px;
            padding: 8px 12px;
        }
        .info-table td {
            font-size: 14px;
            font-weight: 600;
            padding: 8px 12px;
        }
        .stats-card {
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            color: #ffffff;
        }
        .bg-score { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .bg-correct { background: linear-gradient(135deg, #10b981, #047857); }
        .bg-wrong { background: linear-gradient(135deg, #ef4444, #b91c1c); }
        .bg-unanswered { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stats-num { font-size: 24px; font-weight: 700; margin: 0; }
        .stats-label { font-size: 13px; opacity: 0.9; margin: 0; }
        .review-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 14px;
        }
        .review-table td {
            font-size: 13px;
            vertical-align: middle;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
        }
        .signature-box {
            text-align: center;
            border-top: 1px dashed #475569;
            width: 200px;
            padding-top: 8px;
            font-size: 13px;
            font-weight: 600;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .print-container {
                box-shadow: none;
                padding: 20px 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

<div class="print-container">
    <!-- Non-print Action Bar -->
    <div class="no-print d-flex justify-content-between align-items-center mb-25 pb-15 border-bottom">
        <a href="{{ route('exam-papers.show', $examPaper) }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> ফিরে যান (Back)
        </a>
        <div>
            <button onclick="window.print();" class="btn btn-primary btn-sm px-20 font-weight-bold shadow-sm">
                <i class="fa fa-print mr-5"></i> {{ __('messages.print_result_sheet') }}
            </button>
        </div>
    </div>

    <!-- Official Header -->
    <div class="institute-header">
        <img src="{{ asset('backend/images/logo/fateha.jpeg') }}" alt="School Logo" onerror="this.style.display='none'">
        <h2>Fateha School / Madrasa</h2>
        <p>অনলাইন এমসিকিউ পরীক্ষার ফলাফল পত্র (Online MCQ Examination Result Sheet)</p>
    </div>

    <!-- Exam & Student Information -->
    <div class="table-responsive mb-20">
        <table class="table table-bordered info-table mb-0">
            <tbody>
                <tr>
                    <th>{{ __('messages.student_name') }}</th>
                    <td>{{ $submission->student->name ?? 'N/A' }}</td>
                    <th>{{ __('messages.student_id') }}</th>
                    <td>{{ $submission->student->id_no ?? $submission->student->id }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.class') }}</th>
                    <td>{{ $examPaper->studentClass->name ?? 'All' }}</td>
                    <th>{{ __('messages.student_year') }}</th>
                    <td>{{ $examPaper->year->name ?? 'Current' }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.subject') }}</th>
                    <td>{{ $examPaper->subject->name ?? 'General' }}</td>
                    <th>{{ __('messages.exam_type') }}</th>
                    <td>{{ $examPaper->examType->name ?? 'Quiz / Exam' }}</td>
                </tr>
                <tr>
                    <th>পরীক্ষার শিরোনাম (Exam Title)</th>
                    <td>{{ $examPaper->title }}</td>
                    <th>{{ __('messages.submission_date') }}</th>
                    <td>{{ $submission->created_at ? $submission->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Score Summary Stats -->
    <div class="row mb-25">
        <div class="col-3">
            <div class="stats-card bg-score shadow-sm">
                <p class="stats-num">{{ $stats['marks'] }} / {{ $examPaper->max_marks }}</p>
                <p class="stats-label">{{ __('messages.score_obtained') }} ({{ $stats['percentage'] }}%)</p>
            </div>
        </div>
        <div class="col-3">
            <div class="stats-card bg-correct shadow-sm">
                <p class="stats-num">{{ $stats['correct'] }} টি</p>
                <p class="stats-label">সঠিক উত্তর (Correct)</p>
            </div>
        </div>
        <div class="col-3">
            <div class="stats-card bg-wrong shadow-sm">
                <p class="stats-num">{{ $stats['wrong'] }} টি</p>
                <p class="stats-label">ভুল উত্তর (Incorrect)</p>
            </div>
        </div>
        <div class="col-3">
            <div class="stats-card bg-unanswered shadow-sm">
                <p class="stats-num">{{ $stats['unanswered'] }} টি</p>
                <p class="stats-label">অনুত্তরিত (Unanswered)</p>
            </div>
        </div>
    </div>

    <!-- Question by Question Detail Sheet -->
    <h5 class="font-weight-bold mb-10 text-dark">
        <i class="fa fa-list-check text-primary mr-5"></i> বিস্তারিত উত্তরপত্র পর্যালোচনা (Detailed Question Review):
    </h5>
    <div class="table-responsive mb-30">
        <table class="table table-bordered review-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">#</th>
                    <th style="width: 45%;">প্রশ্ন (Question)</th>
                    <th style="width: 20%;">শিক্ষার্থীর উত্তর (Given)</th>
                    <th style="width: 20%;">সঠিক উত্তর (Correct)</th>
                    <th style="width: 10%; text-align: center;">ফলাফল</th>
                </tr>
            </thead>
            <tbody>
                @foreach(($examPaper->questions ?? []) as $qIdx => $q)
                    @php
                        $userGiven = isset($submission->answers[$qIdx]) && $submission->answers[$qIdx] !== '' ? (int) $submission->answers[$qIdx] : null;
                        $correctOpt = (int) ($q['correct_option'] ?? 0);
                        $isCorrect = ($userGiven !== null && $userGiven === $correctOpt);
                        $isUnanswered = ($userGiven === null);
                        
                        $userGivenText = $userGiven !== null && isset($q['options'][$userGiven]) ? chr(65 + $userGiven) . '. ' . $q['options'][$userGiven] : 'উত্তর দেননি (Skipped)';
                        $correctText = isset($q['options'][$correctOpt]) ? chr(65 + $correctOpt) . '. ' . $q['options'][$correctOpt] : 'N/A';
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold">{{ $qIdx + 1 }}</td>
                        <td>{{ $q['question'] ?? '' }}</td>
                        <td>
                            @if($isUnanswered)
                                <span class="text-muted font-italic">— কোনো উত্তর দেননি —</span>
                            @elseif($isCorrect)
                                <span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> {{ $userGivenText }}</span>
                            @else
                                <span class="text-danger font-weight-bold"><i class="fa fa-times-circle"></i> {{ $userGivenText }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-success font-weight-bold">{{ $correctText }}</span>
                        </td>
                        <td class="text-center">
                            @if($isCorrect)
                                <span class="badge badge-success px-8 py-4">সঠিক</span>
                            @elseif($isUnanswered)
                                <span class="badge badge-warning px-8 py-4">অনুত্তরিত</span>
                            @else
                                <span class="badge badge-danger px-8 py-4">ভুল</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Official Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            {{ __('messages.examiner_signature') }}
        </div>
        <div class="signature-box">
            {{ __('messages.headmaster_signature') }}
        </div>
    </div>
</div>

<script>
    // Auto-trigger print if requested via url query ?autoprint=1
    if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
        window.addEventListener('load', function() {
            window.print();
        });
    }
</script>
</body>
</html>
