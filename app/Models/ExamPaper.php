<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamPaper extends Model
{
    protected $fillable = [
        'created_by', 'class_id', 'subject_id', 'exam_type_id',
        'title', 'max_marks', 'question_file', 'answer_key_file',
        'questions', 'is_published', 'published_at',
    ];

    protected $casts = [
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
}
