<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $student = User::where('email', $row['email'])->first();
            if ($student) {

                // Update the student's information
                $student->update([
                    'rfid_number' => $row['rfid'],
                    'username' => $row['username'],
                    'last_name' => $row['last_name'],
                    'middle_name' => $row['middle_name'],
                    'first_name' => $row['first_name'],
                    'role' => 'student',
                    'college_id' => $row['college'],
                    'department_id' => $row['department'],
                    'birthdate' => $row['birthdate'],
                    'phone' => $row['phone'],
                    'status' => $row['status'],
                    'password' => bcrypt($row['username']),
                ]);
            } else {
                // Create a new student
                User::create([
                    'rfid_number' => $row['rfid'],
                    'username' => $row['username'],
                    'last_name' => $row['last_name'],
                    'middle_name' => $row['middle_name'],
                    'first_name' => $row['first_name'],
                    'email' => $row['email'],
                    'role' => 'student',
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
