<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class TeachersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        if ($row['username']) {
            return new Teacher([
                'username' => $row['username'],
                'email' => "",
                'password' => bcrypt($row['password']),
                'sex' => $row['sex'],
                'subjects_id' => $row['subjects_id'],
                'birth_date' => $row['birth_date'],
                'phone_number' => $row['phone_number'],
                'schools_id' => \Auth::guard("school")->id(),
                'is_formal' => $row['is_formal'],
                'is_lock' => 0,
                'remember_token' => Str::random(10),
            ]);
        }
    }
}