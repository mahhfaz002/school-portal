<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'full_name',
        'date_of_birth',
        'gender',
        'parent_name',
        'parent_phone',
        'parent_email',
        'desired_class',
        'status',
        'reason',
        'passport_path',
        'birth_cert_path',
        'indigene_letter_path',
        'passport',
        'admission_number',
        'address',
        'section',
        'fslc_path',
        'junior_waec_path',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    public function age(): ?int
    {
        return $this->date_of_birth ? (int) $this->date_of_birth->age : null;
    }
}
