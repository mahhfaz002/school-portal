<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    // Allow the 'name' field to be saved via create() and update()
    protected $fillable = ['name', 'section'];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'user_id')->withTimestamps();
    }

    /**
     * Relationship: A subject can have many scores
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
