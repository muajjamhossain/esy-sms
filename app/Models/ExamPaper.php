<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPaper extends Model
{
    protected $fillable = [
        'created_by', 'year_id', 'class_id', 'subject_id', 'exam_type_id',
        'title', 'max_marks', 'duration_minutes', 'question_file', 'answer_key_file',
        'questions', 'is_published', 'published_at',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'questions' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function year()
    {
        return $this->belongsTo(StudentYear::class, 'year_id');
    }

    public function subject()
    {
        return $this->belongsTo(SchoolSubject::class, 'subject_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function isMcq(): bool
    {
        return ! empty($this->questions) && is_array($this->questions);
    }

    public function questionCount(): int
    {
        return is_array($this->questions) ? count($this->questions) : 0;
    }
}
