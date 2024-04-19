<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Attendance</h5>
            <button type="button" class="btn btn-primary" id="exportButton">Export</button>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
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
                            <td>{{ $attendance->user->full_name }}</td>
                            <td>Comlab {{ $attendance->laboratory->roomNumber ?? 'N/A'}}</td>
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
                                <span class="badge rounded-pill 
                                    @if ($attendance->status === 'Present') bg-success
                                    @elseif($attendance->status === 'Late') bg-secondary
                                    @elseif($attendance->status === 'Incomplete') bg-danger
                                    @elseif($attendance->status === 'Absent') bg-danger
                                    @endif">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->
    </div>
</div>

<script>
document.getElementById('exportButton').addEventListener('click', function() {
    let route;
    // Determine the route based on the current user's role
    @if (Request::is('students'))
        route = '{{ route('attendanceStudent.tableReport') }}';
    @else
        route = '{{ route('attendance.tableReport') }}';
    @endif
   
    // Send an AJAX request to the determined route for report generation
    fetch(route, {
        method: 'GET',
    })
    .then(response => {
        if (response.ok) {
            // If the response is successful, initiate download
            return response.blob();
        } else {
            // Handle error responses
            console.error('Failed to export report');
            // Optionally, display an error message to the user
        }
    })
    .then(blob => {
        // Create a URL for the blob
        const url = window.URL.createObjectURL(blob);
        // Create a link element
        const a = document.createElement('a');
        // Set the href attribute to the blob URL
        a.href = url;
        // Set the download attribute to specify the file name
        @if (Request::is('students'))
            a.download = 'Student Attendance Report.pdf';
        @else
            a.download = 'User Attendance Report.pdf';
        @endif
        // Append the link to the document body
        document.body.appendChild(a);
        // Click the link to initiate download
        a.click();
        // Remove the link from the document body
        document.body.removeChild(a);
        // Revoke the blob URL to free up memory
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error exporting report:', error);
        // Optionally, display an error message to the user
    });
});
</script>