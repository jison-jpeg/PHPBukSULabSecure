{{-- Add User Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('users.post') }}" method="POST" novalidate>
                    @csrf
                    {{-- <div class="col-md-3">
                        <label for="rfid_number" class="form-label">RFID No.</label>
                        <input type="text" class="form-control" name="rfid_number" placeholder="Scanning...">
                        <div class="invalid-feedback">
                            Please enter an 8-digit valid and unique RFID number.
                        </div>
                    </div> --}}
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
                    <div class="col-md-4">
                        <label for="email" class="form-label">Institutional Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback">
                            Please provide a unique and valid institutional email address.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                        <div class="invalid-feedback">
                            Please provide a unique and valid username.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" required="" name="role">
                            <option selected="" disabled="" value="">Choose...</option>
                            <option value="admin">Admin</option>
                            <option value="support">Support</option>
                            <option value="dean">Dean</option>
                            <option value="chairperson">Chairperson</option>
                            <option value="instructor">Instructor</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a role.
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" placeholder="09123456789"
                            pattern="[0-9]{11}" required>
                        <div class="invalid-feedback">
                            Please provide a valid phone number with 11 digits.
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" name="birthdate" required>
                        <div class="invalid-feedback">
                            Please provide a birthdate.
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
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Update User Modal --}}
@foreach ($users as $user)
    <div class="modal fade" id="updateModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" action="{{ route('users.update', $user->id) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-3">
                            <label for="rfid_number" class="form-label">RFID No.</label>
                            <input type="text" class="form-control" name="rfid_number"
                                value="{{ $user->rfid_number }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name"
                                value="{{ $user->first_name }}" required>
                            <div class="invalid-feedback">
                                Please enter a valid first name.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name"
                                value="{{ $user->middle_name }}">
                            <div class="invalid-feedback">
                                Please enter a valid middle name.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name"
                                value="{{ $user->last_name }}" required>
                            <div class="invalid-feedback">
                                Please enter a valid last name.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Institutional Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a unique and valid institutional email address.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username"
                                value="{{ $user->username }}" required>
                            <div class="invalid-feedback">
                                Please provide a unique and valid username.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" required="" name="role">
                                <option selected="" disabled="" value="">Choose...</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="support" {{ $user->role == 'support' ? 'selected' : '' }}>Support
                                </option>
                                <option value="dean" {{ $user->role == 'dean' ? 'selected' : '' }}>Dean</option>
                                <option value="chairperson" {{ $user->role == 'chairperson' ? 'selected' : '' }}>
                                    Chairperson
                                </option>
                                <option value="instructor" {{ $user->role == 'instructor' ? 'selected' : '' }}>
                                    Instructor
                                </option>
                                <option value="part-time instructor"
                                    {{ $user->role == 'part-time instructor' ? 'selected' : '' }}>
                                    Part-time Instructor</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a role.
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phone" value="{{ $user->phone }}"
                                pattern="[0-9]{11}" required>
                            <div class="invalid-feedback">
                                Please provide a valid phone number with 11 digits.
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate"
                                value="{{ $user->birthdate }}" required>
                            <div class="invalid-feedback">
                                Please provide a birthdate.
                            </div>
                        </div>

                        {{-- @livewire('userdropdown') --}}

                        <div class="col-md-3 col-sm-6">
                            <label for="college_id" class="form-label
                            ">College</label>
                            <select class="form-select" id="college_id" required="" name="college_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}"
                                        {{ $user->college_id == $college->id ? 'selected' : '' }}>
                                        {{ $college->collegeName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a college.
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label for="department_id"
                                class="form-label
                            ">Department</label>
                            <select class="form-select" id="department_id" required="" name="department_id">
                                <option selected="" disabled="" value="">Choose...</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->departmentName }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a department.
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

{{-- Delete User Modal --}}
@foreach ($users as $user)
    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong>{{ $user->last_name }}, {{ $user->first_name }}
                            {{ $user->middle_name }}</strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('users.delete', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

