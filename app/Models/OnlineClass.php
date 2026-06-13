<?php

namespace App\Models;

use App\Models\ClassRecording;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'topic',
        'description',
        'zoom_meeting_id',
        'join_url',
        'start_url',
        'password',
        'start_time',
        'duration',
        'timezone',
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
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function recordings()
    {
        return $this->hasMany(ClassRecording::class, 'online_class_id');
    }


    public function getStatusTextAttribute()
    {
        $statuses = [
            0 => 'Cancelled',
            1 => 'Scheduled',
            2 => 'Ongoing',
            3 => 'Completed'
        ];
        return $statuses[$this->status] ?? 'Unknown';
    }
}
