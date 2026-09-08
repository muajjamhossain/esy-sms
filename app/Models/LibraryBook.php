<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    protected $fillable = ['title', 'author', 'isbn', 'category', 'total_copies', 'available_copies'];

    public function loans()
    {
        return $this->hasMany(LibraryLoan::class, 'book_id');
    }
}
