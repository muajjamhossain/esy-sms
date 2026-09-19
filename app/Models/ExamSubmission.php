<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    protected $fillable = [
        'exam_paper_id', 'student_id', 'answer_file', 'answers', 'correct_count',
        'wrong_count', 'unanswered_count', 'total_questions', 'ai_marks',
        'final_marks', 'ai_feedback', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'reviewed_at' => 'datetime',
        'correct_count' => 'integer',
        'wrong_count' => 'integer',
        'unanswered_count' => 'integer',
        'total_questions' => 'integer',
    ];

    public function examPaper()
    {
        return $this->belongsTo(ExamPaper::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Compute or retrieve MCQ performance statistics.
     */
    public function getMcqStats(): array
    {
        $paper = $this->examPaper;
        $questions = $paper ? ($paper->questions ?? []) : [];
        $totalQuestions = count($questions);

        if ($totalQuestions === 0) {
            return [
                'total' => 0,
                'correct' => 0,
                'wrong' => 0,
                'unanswered' => 0,
                'marks' => (float) ($this->final_marks ?? 0),
                'percentage' => 0,
            ];
        }

        // If counts were cached in DB
        if ($this->correct_count !== null && $this->wrong_count !== null) {
            $correct = (int) $this->correct_count;
            $wrong = (int) $this->wrong_count;
            $unanswered = (int) ($this->unanswered_count ?? max(0, $totalQuestions - ($correct + $wrong)));
        } else {
            $answers = is_array($this->answers) ? $this->answers : [];
            $correct = 0;
            $wrong = 0;
            $unanswered = 0;

            foreach ($questions as $index => $q) {
                if (! isset($answers[$index]) || $answers[$index] === '' || $answers[$index] === null) {
                    $unanswered++;
                } elseif ((int) $answers[$index] === (int) ($q['correct_option'] ?? -1)) {
                    $correct++;
                } else {
                    $wrong++;
                }
            }
        }

        $maxMarks = (float) ($paper ? $paper->max_marks : 100);
        $finalMarks = $this->final_marks !== null
            ? (float) $this->final_marks
            : round(($correct / $totalQuestions) * $maxMarks, 2);

        $percentage = $maxMarks > 0 ? round(($finalMarks / $maxMarks) * 100, 1) : 0;

        return [
            'total' => $totalQuestions,
            'correct' => $correct,
            'wrong' => $wrong,
            'unanswered' => $unanswered,
            'marks' => $finalMarks,
            'percentage' => $percentage,
        ];
    }
}
