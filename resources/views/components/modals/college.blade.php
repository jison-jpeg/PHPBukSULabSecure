{{-- Add Subject Modal --}}
<div class="modal fade" id="addCollegeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New College</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('colleges.post') }}" method="POST" novalidate>
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
                    {{-- <div class="col-md-4">
                        <label for="address" class="form-label">Section Code</label>
                        <input type="text" class="form-control" name="address" placeholder="ex. T321">
                    </div> --}}
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
