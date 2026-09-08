<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalConversation extends Model
{
    protected $fillable = ['created_by', 'participant_id', 'subject', 'status'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function messages()
    {
        return $this->hasMany(PortalMessage::class, 'conversation_id');
    }
}
