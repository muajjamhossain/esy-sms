@extends('admin.admin_master')
@section('admin')
@php
    $user = auth()->user();
    $isStudent = strtolower((string) ($user->role ?: $user->usertype)) === 'student';
@endphp

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="d-flex justify-content-between align-items-center mb-20 flex-wrap">
                <div>
                    <h3 class="font-weight-bold text-dark mb-5">
                        <i class="fa fa-pencil-square text-primary mr-5"></i> {{ __('messages.exam_papers') }}
                    </h3>
                    <p class="text-muted mb-0 font-size-14">{{ __('messages.exam_paper_help') }}</p>
                </div>
                @if(! $isStudent)
                    <div class="mt-10">
                        <a href="{{ route('exam-papers.create') }}" class="btn btn-primary btn-rounded shadow-sm font-weight-bold">
                            <i class="fa fa-plus-circle mr-5"></i> {{ __('messages.create_exam_paper') }}
                        </a>
                    </div>
                @endif
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle mr-5"></i> {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="box shadow-sm" style="border-radius: 10px;">
                <div class="box-body p-0 table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 30%;">পরীক্ষার শিরোনাম (Title)</th>
                                <th>{{ __('messages.class') }} / শিক্ষাবর্ষ</th>
                                <th>{{ __('messages.subject') }}</th>
                                <th class="text-center">ধরন (Type)</th>
                                <th class="text-center">{{ __('messages.max_marks') }}</th>
                                <th class="text-center">অবস্থা (Status)</th>
                                <th class="text-right">একশন</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($papers as $paper)
                            @php
                                $studentSubmission = $isStudent ? $paper->submissions->firstWhere('student_id', $user->id) : null;
                                $subStats = $studentSubmission ? $studentSubmission->getMcqStats() : null;
                            @endphp
                            <tr>
                                <td>
                                    <strong class="font-size-15 text-dark">{{ $paper->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $paper->examType->name ?? 'Exam' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light text-dark font-size-13">{{ $paper->studentClass->name ?? __('messages.all_classes') }}</span>
                                    <br>
                                    <small class="text-muted">{{ $paper->year->name ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="font-weight-600 text-dark">{{ $paper->subject->name ?? __('messages.general') }}</span>
                                </td>
                                <td class="text-center">
                                    @if($paper->isMcq())
                                        <span class="badge badge-info px-8 py-4 font-size-12">
                                            <i class="fa fa-check-circle mr-3"></i> এমসিকিউ ({{ $paper->questionCount() }}টি)
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-8 py-4 font-size-12">
                                            <i class="fa fa-file-text-o mr-3"></i> ফাইল আপলোড
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center font-weight-bold font-size-15 text-primary">
                                    {{ $paper->max_marks }}
                                </td>
                                <td class="text-center">
                                    @if($isStudent)
                                        @if($studentSubmission && $paper->is_published)
                                            <span class="badge badge-success px-8 py-4">
                                                <i class="fa fa-check"></i> প্রাপ্ত নম্বর: {{ $subStats['marks'] }}/{{ $paper->max_marks }}
                                            </span>
                                        @elseif($studentSubmission)
                                            <span class="badge badge-warning text-dark px-8 py-4">
                                                <i class="fa fa-clock-o"></i> জমা হয়েছে (ফলাফল অপেক্ষমাণ)
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-8 py-4">
                                                <i class="fa fa-pencil"></i> পরীক্ষা বাকি
                                            </span>
                                        @endif
                                    @else
                                        @if($paper->is_published)
                                            <span class="badge badge-success px-8 py-4">
                                                <i class="fa fa-check-circle mr-3"></i> প্রকাশিত
                                            </span>
                                        @else
                                            <span class="badge badge-warning text-dark px-8 py-4">
                                                <i class="fa fa-clock-o mr-3"></i> খসড়া / অপ্রকাশিত
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-info btn-rounded shadow-sm px-15" href="{{ route('exam-papers.show', $paper) }}">
                                        @if($isStudent)
                                            @if($studentSubmission && $paper->is_published)
                                                <i class="fa fa-eye mr-3"></i> রেজাল্ট ও প্রিন্ট
                                            @elseif($studentSubmission)
                                                <i class="fa fa-eye mr-3"></i> অবস্থা দেখুন
                                            @else
                                                <i class="fa fa-play-circle mr-3"></i> পরীক্ষা দিন
                                            @endif
                                        @else
                                            <i class="fa fa-eye mr-3"></i> পর্যালোচনা ও ফলাফল
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-30 text-muted">
                                    <i class="fa fa-file-text-o font-size-30 d-block mb-10"></i>
                                    {{ __('messages.no_assignments') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
