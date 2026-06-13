<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_type_id',
        'class_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'room_no',
        'full_mark',
        'pass_mark',
        'notes',
        'status'
    ];

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(SchoolSubject::class, 'subject_id');
    }
}
