<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRecording extends Model
{
    use HasFactory;

    protected $table = 'class_recordings';

    protected $fillable = [
        'online_class_id',
        'recording_url',
        'recording_type',
        'file_size',
        'recording_date',
        'duration',
        'download_url'
    ];

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class, 'online_class_id');
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return 'N/A';

        $bytes = (int)$this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
