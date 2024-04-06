<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Schedule</h5>
            <button type="button" class="btn btn-primary">Export</button>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>College</th>
                        <th>Department</th>
                        <th scope="col">Subject Code</th>
                        <th scope="col">Section Code</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Instructor</th>
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
                            <td>{{ $schedule->college->collegeName }}</td>
                            <td>{{ $schedule->department->departmentName }}</td>
                            <td>{{ $schedule->subject->subjectCode }}</td>
                            <th>{{ $schedule->sectionCode }}</th>
                            <td>{{ $schedule->subject->subjectName }}</td>
                            <td>{{ $schedule->user->full_name }}</td>
                            <td>Comlab {{ $schedule->laboratory->roomNumber }}</td>
                            <td>{{ $schedule->days }}</td>
                            <td>{{ date('h:i A', strtotime($schedule->start_time)) }}</td>
                            <td>{{ date('H:i A', strtotime($schedule->end_time)) }}</td>
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
                                    {{-- <div class="mx-1"></div>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#archiveModal">
                                        Archive
                                    </button>
                                    <div class="mx-1"></div>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#restoreModal">
                                        Restore
                                    </button> --}}
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
