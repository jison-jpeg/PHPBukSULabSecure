{{-- Add Subject Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="" method="POST">
                    @csrf
                    <div class="col-md-6">
                        <label for="dorm_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" name="dorm_name" placeholder="Name of the subject">
                    </div>
                    <div class="col-md-6">
                        <label for="tenant" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" name="address" placeholder="ex. IT123">
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" style="height: 100px" placeholder="ex. This is a description of a subject."></textarea>
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
