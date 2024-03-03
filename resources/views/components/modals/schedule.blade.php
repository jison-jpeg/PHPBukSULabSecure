{{-- Add Schedule Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Schedule</h5>
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
                        <label for="section_code" class="form-label">Section Code</label>
                        <select class="form-select" id="sectionCode" required="" name="sectionCode">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->sectionCode }}">{{ $subject->sectionCode }}</option>
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
                                <option value="{{ $laboratory->id }}">{{ $laboratory->roomNumber }}</option>
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
                        <div class="d-md-flex justify-content-md-between flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="monday"
                                    value="M">
                                <label class="form-check-label" for="monday">Monday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="tuesday"
                                    value="T">
                                <label class="form-check-label" for="tuesday">Tuesday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="wednesday"
                                    value="W">
                                <label class="form-check-label" for="wednesday">Wednesday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="thursday"
                                    value="TH">
                                <label class="form-check-label" for="thursday">Thursday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="friday"
                                    value="F">
                                <label class="form-check-label" for="friday">Friday</label>
                            </div>
                        </div>
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
