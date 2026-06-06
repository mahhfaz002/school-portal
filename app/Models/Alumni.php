<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';

    protected $fillable = ['full_name', 'email', 'phone', 'graduation_year', 'current_occupation', 'location'];
}
