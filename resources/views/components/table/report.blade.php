<h5 class="card-title">Attendance</h5>

        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Room</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Time In</th>
                        <th scope="col">Time Out</th>
                        <th scope="col">Date</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Percentage</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uniqueAttendances as $key => $attendance)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $attendance->id }}</td>
                            <td>{{ $attendance->user->full_name }}</td>
                            <td>Comlab {{ $attendance->laboratory->roomNumber }}</td>
                            <td>{{ $attendance->subject->subjectName }}</td>
                            <td>{{ $attendance->time_in }}</td>
                            <td>{{ $attendance->time_out }}</td>
                            <td>{{ $attendance->date }}</td>
                            <td>{{ $attendance->total_duration }}</td>

                            <td>
                                <div class="progress mt-1-5">
                                    <div class="progress-bar 
                                    @if ($attendance->percentage < 33) bg-danger
                                    @elseif ($attendance->percentage < 66)
                                        bg-warning
                                    @else
                                        bg-success @endif"
                                        role="progressbar" style="width: {{ $attendance->percentage }}%"
                                        aria-valuenow="{{ $attendance->percentage }}" aria-valuemin="0"
                                        aria-valuemax="100">{{ $attendance->percentage }}%</div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge rounded-pill 
                                    @if ($attendance->status === 'Present') bg-success
                                    @elseif($attendance->status === 'Incomplete') bg-danger
                                    @elseif($attendance->status === 'Absent') bg-danger
                                    @elseif($attendance->status === 'Late') bg-secondary @endif">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->

