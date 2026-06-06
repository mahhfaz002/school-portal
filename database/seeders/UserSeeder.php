<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Proprietor Office', 'proprietor@excellence.edu', 'proprietor'],
            ['School Principal',  'principal@excellence.edu',  'principal'],
            ['System Admin',      'admin@excellence.edu',      'admin'],
            ['ICT Administrator', 'ict@excellence.edu',        'ict'],
            ['School Bursar',     'bursar@excellence.edu',     'accountant'],
            ['Exam Officer',      'exams@excellence.edu',      'exam_officer'],
            ['Mr. Jalingo',       'teacher@excellence.edu',    'teacher'],
        ];

        foreach ($users as [$name, $email, $role]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'must_change_password' => false,
                ]
            );
        }

        // Give the demo teacher a class so their dashboard has data.
        User::where('email', 'teacher@excellence.edu')->update(['class_assigned' => 'JSS1A']);
    }
}
