<?php

namespace App\Jobs;

use App\Models\ExamSubmission;
use App\Services\ExamGradingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GradeExamSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 240;

    public $submissionId;

    public function __construct($submissionId)
    {
        $this->submissionId = $submissionId;
        $this->onQueue('exam-grading');
    }

    public function handle(ExamGradingService $grader)
    {
        $submission = ExamSubmission::with('examPaper')->find($this->submissionId);
        if (! $submission) {
            return;
        }

        $result = $grader->grade($submission->examPaper, $submission);
        if ($result) {
            $submission->update([
                'ai_marks' => $result['marks'],
                'ai_feedback' => $result['feedback'],
            ]);
        } else {
            $submission->update(['ai_feedback' => 'AI grading did not return a result. Teacher review is required.']);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Exam grading job failed.', [
            'submission_id' => $this->submissionId,
            'error' => $exception->getMessage(),
        ]);

        ExamSubmission::whereKey($this->submissionId)->update([
            'ai_feedback' => 'Automatic grading failed. Teacher review is required.',
        ]);
    }
}
