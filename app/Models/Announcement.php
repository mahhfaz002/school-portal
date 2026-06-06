<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'audience', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Announcements visible to a given role.
     */
    public function scopeVisibleTo($query, string $role)
    {
        $audiences = ['all'];
        $audiences[] = $role;
        if ($role !== 'student') {
            $audiences[] = 'staff';
        }
        return $query->where('is_published', true)->whereIn('audience', $audiences);
    }
}
