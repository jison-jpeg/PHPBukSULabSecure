<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FacultyImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $faculty = User::where('email', $row['email'])->first();
            if ($faculty) {

                // Update the faculty's information
                $faculty->update([
                    'username' => $row['username'],
                    'last_name' => $row['last_name'],
                    'middle_name' => $row['middle_name'],
                    'first_name' => $row['first_name'],
                    'role' => 'instructor',
                    'college_id' => $row['college'],
                    'department_id' => $row['department'],
                    'birthdate' => $row['birthdate'],
                    'phone' => $row['phone'],
                    'status' => $row['status'],
                    'password' => bcrypt($row['username']),
                ]);
            } else {
                // Create a new faculty
                User::create([
                    'username' => $row['username'],
                    'last_name' => $row['last_name'],
                    'middle_name' => $row['middle_name'],
                    'first_name' => $row['first_name'],
                    'email' => $row['email'],
                    'role' => 'instructor',
                    'college_id' => $row['college'],
                    'department_id' => $row['department'],
                    'birthdate' => $row['birthdate'],
                    'phone' => $row['phone'],
                    'status' => $row['status'],
                    'password' => bcrypt($row['username']),
                ]);
            }
        }
    }
}
