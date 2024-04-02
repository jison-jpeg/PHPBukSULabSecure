{{-- Add Department Modal --}}
<div class="modal fade" id="addDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('departments.post') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <label for="college_id" class="form-label">College</label>
                        <select class="form-select" id="college_id" required="" name="college_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->collegeName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a college.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="departmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" name="departmentName" placeholder="Department Name" required>
                        <div class="invalid-feedback">
                            Please enter the department name.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="departmentDescription" class="form-label">Department Description</label>
                        <textarea class="form-control" style="height: 100px" name="departmentDescription" placeholder="Department Description"></textarea>
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

{{-- Update Department Modal --}}
@foreach ($departments as $department)
<div class="modal fade" id="updateDepartmentModal{{ $department->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('departments.update', $department->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <label for="college_id" class="form-label">College</label>
                        <select class="form-select" id="college_id" required="" name="college_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}"
                                        {{ $department->college_id == $college->id ? 'selected' : '' }}>
                                        {{ $college->collegeName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a college.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="departmentName" class="form-label">Department Name</label>
                        <input type="text" class="form-control" name="departmentName" 
                            value="{{ $department->departmentName }}" required>
                        <div class="invalid-feedback">
                            Please enter a valid department name.
                        </div>
                    </div>
                    <div class="col-md-12">
                            <label for="departmentDescription" class="form-label">Department Description</label>
                            <input type="text" class="form-control" name="departmentDescription"
                                value="{{ $department->departmentDescription }}" required>
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

{{-- Delete Department Modal --}}
@foreach ($departments as $department)
    <div class="modal fade" id="deleteDepartmentModal{{ $department->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong>{{ $department->departmentName }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('departments.delete', ['id' => $department->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

