<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'employee_id',
        'routine_day_id',
        'start_time_id',
        'end_time_id',
        'room_no',
        'note',
        'status'
    ];

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(SchoolSubject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function routineDay()
    {
        return $this->belongsTo(RoutineDay::class, 'routine_day_id');
    }

    public function startTime()
    {
        return $this->belongsTo(ClassTime::class, 'start_time_id');
    }

    public function endTime()
    {
        return $this->belongsTo(ClassTime::class, 'end_time_id');
    }
}
