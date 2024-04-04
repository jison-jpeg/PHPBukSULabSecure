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
                        <th scope="col">#</th>
                        <th>College</th>
                        <th>Department</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Section Code</th>
                        <th scope="col">Instructor</th>
                        <th scope="col">Days</th>
                        <th scope="col">Room</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <th scope="row">{{ $schedule->subject->subjectCode }}</th>
                            <td>{{ $schedule->college->collegeName }}</td>
                            <td>{{ $schedule->department->departmentName }}</td>
                            <td>{{ $schedule->subject->subjectName }}</td>
                            <td>{{ $schedule->sectionCode }}</td>
                            <td>{{ $schedule->user->full_name }}</td>
                            <td>{{ $schedule->days }}</td>
                            <td>Comlab {{ $schedule->laboratory->roomNumber }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{$schedule->id}}">
                                        Edit
                                    </button>
                                    <div class="mx-1"></div>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
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
