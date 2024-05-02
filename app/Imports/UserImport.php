<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $user = User::where('email', $row['email'])->first();
            if ($user) {

                // Update the user's information
                $user->update([
                    'rfid_number' => $row['rfid'],
                    'username' => $row['username'],
                    'last_name' => $row['last_name'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'role' => $row['role'],
                    'college_id' => $row['college'],
                    'department_id' => $row['department'],
                    'birthdate' => $row['birthdate'],
                    'phone' => $row['phone'],
                    'status' => $row['status'],
                    'password' => bcrypt($row['username']),
                ]);
            } else {
                // Create a new user
                User::create([
                    'rfid_number' => $row['rfid'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'last_name' => $row['last_name'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'role' => $row['role'],
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
