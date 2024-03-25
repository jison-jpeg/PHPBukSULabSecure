<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Attendance</h5>
            <button type="button" class="btn btn-primary">Export</button>
        </div>
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
                        <th scope="col">Percentage</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $key => $attendance)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $attendance->id }}</td>
                            <td>{{ $attendance->user->full_name }}</td>
                            <td>Comlab {{ $attendance->laboratory->roomNumber }}</td>
                            <td>{{ $attendance->subject->subjectName }}</td>
                            <td>{{ $attendance->time_in }}</td>
                            <td>{{ $attendance->time_out }}</td>
                            <td>{{ $attendance->created_at->format('m-d-Y') }}</td>

                            <td>
                                <div class="progress mt-1-5">
                                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25"
                                        aria-valuemin="0" aria-valuemax="100">{{ $attendance->percentage  }}</div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="badge rounded-pill {{ $attendance->status === 'Present' ? 'bg-success' : 'bg-danger' }}">{{ $attendance->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->
    </div>
</div>
