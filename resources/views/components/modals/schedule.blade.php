{{-- Add Schedule Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('schedules.post') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <label for="college_id" class="form-label">College</label>
                        <select class="form-select" id="college_id" required="" name="college_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->collegeName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a subject.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" required="" name="department_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->departmentName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a subject.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="subject_id" class="form-label">Subject Name</label>
                        <select class="form-select" id="subject_id" required="" name="subject_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subjectName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a subject.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="section_id" class="form-label">Section Code</label>
                        <select class="form-select" id="section_id" required="" name="section_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->sectionCode }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a subject.
                        </div>
                    </div>


                    <div class="col-md-3">
                        <label for="user_id" class="form-label">Instructor</label>
                        <select class="form-select" id="user_id" required="" name="user_id" required>
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->first_name }}
                                    {{ $instructor->middle_name }} {{ $instructor->last_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select an instructor.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="laboratory_id" class="form-label">Room</label>
                        <select class="form-select" id="laboratory_id" required="" name="laboratory_id" required>
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($laboratories as $laboratory)
                                <option value="{{ $laboratory->id }}">Comlab {{ $laboratory->roomNumber }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a room to asign.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start_time" required>
                        <div class="invalid-feedback">
                            Please enter a start time.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end_time" required>
                        <div class="invalid-feedback">
                            Please enter an end time.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Days</label><br>
                        @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="days[]"
                                    value="{{ $day }}" id="{{ $day }}" required>
                                <label class="form-check-label"
                                    for="{{ $day }}">{{ $day }}</label>
                            </div>
                        @endforeach
                        <div class="invalid-feedback">
                            Please select at least one day.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Schedule --}}
@foreach ($schedules as $schedule)
    <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" action="{{ route('schedules.update', $schedule->id) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="college_id" class="form-label">College</label>
                            <select class="form-select" id="college_id" required="" name="college_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}"
                                        {{ $college->id == $schedule->college_id ? 'selected' : '' }}>
                                        {{ $college->collegeName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a subject.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select" id="department_id" required="" name="department_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ $department->id == $schedule->department_id ? 'selected' : '' }}>
                                        {{ $department->departmentName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a subject.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="subject_id" class="form-label">Subject Name</label>
                            <select class="form-select" id="subject_id" required="" name="subject_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ $subject->id == $schedule->subject_id ? 'selected' : '' }}>
                                        {{ $subject->subjectName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a subject.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="section_id" class="form-label">Section Code</label>
                            <select class="form-select" id="section_id" required="" name="section_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ $section->id == $schedule->section_id ? 'selected' : '' }}>
                                        {{ $section->sectionCode }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a subject.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">Instructor</label>
                            <select class="form-select" id="user_id" required="" name="user_id" required>
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}"
                                        {{ $instructor->id == $schedule->user_id ? 'selected' : '' }}>
                                        {{ $instructor->first_name }} {{ $instructor->middle_name }}
                                        {{ $instructor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select an instructor.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="laboratory_id" class="form-label">Room</label>
                            <select class="form-select" id="laboratory_id" required="" name="laboratory_id"
                                required>
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($laboratories as $laboratory)
                                    <option value="{{ $laboratory->id }}"
                                        {{ $laboratory->id == $schedule->laboratory_id ? 'selected' : '' }}>
                                        Comlab {{ $laboratory->roomNumber }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a room to asign.
                            </div>

                        </div>
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="start_time"
                                value="{{ date('H:i', strtotime($schedule->start_time)) }}" required>
                            <div class="invalid-feedback">
                                Please enter a start time.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control" name="end_time"
                                value="{{ date('H:i', strtotime($schedule->end_time)) }}" required>
                            <div class="invalid-feedback">
                                Please enter an end time.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Days</label><br>

                            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="days[]"
                                        value="{{ $day }}" id="{{ $day }}"
                                        {{ in_array($day, explode(',', $schedule->days)) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="{{ $day }}">{{ $day }}</label>
                                </div>
                            @endforeach
                            <div class="invalid-feedback">
                                Please select at least one day.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
{{-- End Edit Schedule --}}

{{-- Delete Schedule --}}
@foreach ($schedules as $schedule)
    <div class="modal fade" id="deleteModal{{ $schedule->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete this schedule for
                        <br><strong>{{ $schedule->subject->subjectName }}</strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('schedules.delete', $schedule->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Import Schedule --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label for="file" class="form-label">Upload File</label>
                        <input class="form-control" type="file" name="file" id="file" accept=".xlsx, .xls" required>
                        <div class="invalid-feedback">
                            Please upload a valid Excel file (.xlsx or .xls).
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

