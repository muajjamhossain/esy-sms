<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryLoan extends Model
{
    protected $fillable = ['book_id', 'borrower_id', 'issued_by', 'issued_at', 'due_at', 'returned_at'];

    protected $casts = [
        'issued_at' => 'date',
        'due_at' => 'date',
        'returned_at' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
