<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAdmitCard extends Model
{
    use HasFactory;

    protected $table = 'exam_admit_cards';

    protected $fillable = [
        'student_id',
        'class_id',
        'exam_type_id',
        'roll_no',
        'registration_no',
        'admit_card_no',
        'issue_date',
        'exam_center',
        'room_no',
        'seat_no',
        'instructions',
        'signature_path',
        'photo_path',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }
}
