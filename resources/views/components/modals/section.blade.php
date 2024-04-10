{{-- Add Section Modal --}}
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('sections.create') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label for="sectionCode" class="form-label">Section Name</label>
                        <input type="text" class="form-control" name="sectionCode" required>
                        <div class="invalid-feedback">
                            Please enter a valid section name.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" required="" name="department_id">
                            <option selected="" disabled="" value="">Choose...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->departmentName }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a department.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="sectionDescription" class="form-label">Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" style="height: 100px" name="sectionDescription" id="sectionDescription" placeholder="ex. This is a description about this section."></textarea>
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

{{-- Update Section Modal --}}
@foreach ($sections as $section)
    <div class="modal fade" id="updateSectionModal{{ $section->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" action="{{ route('sections.update', $section->id) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="sectionCode" class="form-label">Section Name</label>
                            <input type="text" class="form-control" name="sectionCode"
                                value="{{ $section->sectionCode }}" required>
                            <div class="invalid-feedback">
                                Please enter a valid section name.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="sectionDescription" class="form-label">Section Description</label>
                            <input type="text" class="form-control" name="sectionDescription"
                                value="{{ $section->sectionDescription }}" required>
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

{{-- Delete Section Modal --}}
@foreach ($sections as $section)
    <div class="modal fade" id="deleteSectionModal{{ $section->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong>{{ $section->sectionCode }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('sections.delete', ['id' => $section->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

