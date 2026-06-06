<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['isbn', 'title', 'author', 'total_copies', 'available_copies'];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
