<?php

namespace App\Imports;

use App\Models\Schedule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScheduleImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Check if all fields are null
            if ($this->allFieldsNull($row)) {
                continue; // Skip this row
            }

            // Check if the schedule already exists based on some criteria
            $existingSchedule = Schedule::where([
                'college_id' => $row['college'],
                'department_id' => $row['department'],
                'subject_id' => $row['subject'],
                'section_id' => $row['section'],
                'user_id' => $row['user'],
                'laboratory_id' => $row['laboratory'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'days' => $row['days'],
            ])->first();

            // If the schedule already exists, skip insertion
            if ($existingSchedule) {
                continue;
            }

            // Otherwise, create the schedule
            Schedule::create([
                'college_id' => $row['college'],
                'department_id' => $row['department'],
                'subject_id' => $row['subject'],
                'section_id' => $row['section'],
                'user_id' => $row['user'],
                'laboratory_id' => $row['laboratory'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'days' => $row['days'],
            ]);
        }
    }

    /**
     * Check if all fields in the given row are null.
     *
     * @param array $row
     * @return bool
     */
    private function allFieldsNull($row)
    {
        foreach ($row as $field) {
            if (!is_null($field)) {
                return false;
            }
        }
        return true;
    }
}
