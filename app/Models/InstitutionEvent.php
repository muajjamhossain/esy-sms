<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionEvent extends Model
{
    protected $fillable = [
        'created_by', 'class_id', 'title', 'type', 'description',
        'location', 'starts_at', 'ends_at', 'audience',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where(function ($date) {
            $date->whereNull('ends_at')->where('starts_at', '>=', now())
                ->orWhereNotNull('ends_at')->where('ends_at', '>=', now());
        });
    }
}
