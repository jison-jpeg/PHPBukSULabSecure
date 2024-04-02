{{-- Add College Modal --}}
<div class="modal fade" id="addCollegeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New College</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('colleges.create') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label for="collegeName" class="form-label">College Name</label>
                        <input type="text" class="form-control" name="collegeName" placeholder="Name of the college" required>
                        <div class="invalid-feedback">
                            Please enter a short description about this college.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="collegeDescription" class="form-label">Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" style="height: 100px" name="collegeDescription" id="collegeDescription" placeholder="ex. This is a description about this college."></textarea>
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

{{-- Update College Modal --}}
@foreach ($colleges as $college)
    <div class="modal fade" id="updateModal{{ $college->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" action="{{ route('college.update', $college->id) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="collegeName" class="form-label">College Name</label>
                            <input type="text" class="form-control" name="collegeName"
                                value="{{ $college->collegeName }}" required>
                            <div class="invalid-feedback">
                                Please enter a valid college name.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="collegeDescription" class="form-label">College Description</label>
                            <input type="text" class="form-control" name="collegeDescription"
                                value="{{ $college->collegeDescription }}" required>
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

{{-- Delete College Modal --}}
@foreach ($colleges as $college)
    <div class="modal fade" id="deleteModal{{ $college->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong>{{ $college->collegeName }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('college.delete', ['id' => $college->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

