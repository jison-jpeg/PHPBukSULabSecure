{{-- Add Student Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('students.post') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" required>
                        <div class="invalid-feedback">
                            Please enter a valid first name.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name">
                        <div class="invalid-feedback">
                            Please enter a valid middle name.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required>
                        <div class="invalid-feedback">
                            Please enter a valid last name.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Institutional Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback">
                            Please provide a unique and valid institutional email address.
                        </div>
                    </div>

                    {{-- @livewire('userdropdown') --}}

                    <div class="col-md-3 col-sm-6">
                        <label for="college_id" class="form-label">College</label>
                        <select class="form-select" id="college_id" required="" name="college_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->collegeName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a college.
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" required="" name="department_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->departmentName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a department.
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label for="section_code" class="form-label">Section Code</label>
                        <select class="form-select" id="section_code" required="" name="section_code">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->sectionCode }}">{{ $section->sectionCode }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a section code.
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

{{-- Edit Student Modal --}}
@foreach ($users as $user)
    <div class="modal fade" id="updateModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" action="{{ route('students.put', $user->id) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name"
                                value="{{ $user->first_name }}" required>
                            <div class="invalid-feedback">
                                Please enter a valid first name.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name"
                                value="{{ $user->middle_name }}">
                            <div class="invalid-feedback">
                                Please enter a valid middle name.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name"
                                value="{{ $user->last_name }}" required>
                            <div class="invalid-feedback">
                                Please enter a valid last name.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Institutional Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a unique and valid institutional email address.
                            </div>
                        </div>

                        {{-- @livewire('userdropdown') --}}

                        <div class="col-md-3 col-sm-6">
                            <label for="college_id" class="form-label">College</label>

                            <select class="form-select" id="college_id" required="" name="college_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}"
                                        @if ($user->college_id == $college->id) selected @endif>
                                        {{ $college->collegeName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a college.
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="department_id" class="form-label ">Department</label>
                            <select class="form-select" id="department_id" required="" name="department_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        @if ($user->department_id == $department->id) selected @endif>
                                        {{ $department->departmentName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a department.
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="section_code" class="form-label">Section Code</label>
                            <select class="form-select" id="section_code" required="" name="section_code">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->sectionCode }}"
                                        @if ($user->section_code == $section->sectionCode) selected @endif>
                                        {{ $section->sectionCode }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a section code.
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
@endforeach
{{-- End of Student Modal --}}

{{-- Delete Student Modal --}}
@foreach ($users as $user)
    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong>{{ $user->last_name }}, {{ $user->first_name }}
                            {{ $user->middle_name }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('students.delete', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
{{-- End of Student Modal --}}
