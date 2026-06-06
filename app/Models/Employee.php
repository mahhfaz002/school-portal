<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'email', 'position', 'base_salary', 'bank_account_number'];

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
}
