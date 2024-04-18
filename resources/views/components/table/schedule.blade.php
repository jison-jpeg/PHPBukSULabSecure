<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">
                @if(Request::is('schedules/instructor/*'))
                    Schedule
                @elseif(Request::is('subjects/user/*'))
                    Subjects
                @else
                    Schedule
                @endif
            </h5>
            <button type="button" class="btn btn-primary" id="exportButton">Export</button>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        @if(!Request::is('schedules/instructor/*') && !Request::is('subjects/user/*'))
                            <th>College</th>
                            <th>Department</th>
                        @endif
                        @unless(Request::is('subjects/user/*'))
                            <th scope="col">Instructor</th>
                        @endunless
                        <th scope="col">Subject Code</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Section Code</th>
                        @if(Request::is('subjects/user/*'))
                            <th scope="col">Instructor</th>
                        @endif
                        <th scope="col">Room</th>
                        <th scope="col">Days</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            @if(!Request::is('schedules/instructor/*') && !Request::is('subjects/user/*'))
                                <td>{{ $schedule->college->collegeName }}</td>
                                <td>{{ $schedule->department->departmentName }}</td>
                            @endif
                            @unless(Request::is('subjects/user/*'))
                                <td>{{ $schedule->user->full_name }}</td>
                            @endunless
                            <td>{{ $schedule->subject->subjectCode }}</td>
                            {{-- View attendance of the user based on section and subject from schedule --}}
                            <td>
                                <a href="{{ route('attendance.student', ['sectionId' => $schedule->section->id, 'subjectId' => $schedule->subject->id]) }}">
                                    {{ $schedule->subject->subjectName }}
                                </a>
                            </td>
                            <td>{{ $schedule->section->sectionCode }}</td>
                            @if(Request::is('subjects/user/*'))
                                <td>{{ $schedule->user->full_name }}</td>
                            @endif
                            <td>Comlab {{ $schedule->laboratory->roomNumber }}</td>
                            <td>{{ $schedule->days }}</td>
                            <td>{{ date('h:i A', strtotime($schedule->start_time)) }}</td>
                            <td>{{ date('h:i A', strtotime($schedule->end_time)) }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{$schedule->id}}">
                                        Edit
                                    </button>
                                    <div class="mx-1"></div>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{$schedule->id}}">
                                        Delete
                                    </button>
                                </div>
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
    // Send an AJAX request to the route that generates the report
    fetch('{{ route('schedule.tableReport') }}', {
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
        a.download = 'Schedule List Report.pdf'; // Change the file name as needed
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