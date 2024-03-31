<?php

namespace App\Jobs;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    public function handle()
    {
        // Check if the user hasn't returned within 15 minutes
        if ($this->attendance->time_out && $this->attendance->time_out->addMinutes(15)->lte(now())) {
            // Update attendance status to "INCOMPLETE"
            $this->attendance->update(['status' => 'INCOMPLETE']);
        }
    }
}

