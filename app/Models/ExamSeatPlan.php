<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSeatPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_type_id',
        'class_id',
        'room_no',
        'row_no',
        'column_no',
        'seat_no',
        'student_id',
        'roll_no',
        'bench_no'
    ];

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
