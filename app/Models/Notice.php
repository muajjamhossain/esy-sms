<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['created_by', 'class_id', 'title', 'body', 'audience', 'published_at', 'expires_at'];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function scopeVisible($query)
    {
        return $query->where(function ($published) {
            $published->whereNull('published_at')->orWhere('published_at', '<=', now());
        })->where(function ($expires) {
            $expires->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        });
    }
}
