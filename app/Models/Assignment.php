<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['created_by', 'class_id', 'subject_id', 'title', 'instructions', 'due_date'];

    protected $casts = ['due_date' => 'date'];

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

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
