<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAssignment extends Model
{
    protected $fillable = ['student_id', 'route_id', 'vehicle_id', 'pick_up_point'];
}
