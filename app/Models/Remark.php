<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    protected $fillable = ['student_id', 'term', 'session', 'teacher_comment'];
}
