{{-- Add Laboratory Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Laboratory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('laboratories.post') }}" method="POST" novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="roomNumber" class="form-label">Comlab No.</label>
                        <input type="number" class="form-control" name="roomNumber" placeholder="ex. 1" required>
                        <div class="invalid-feedback">
                            Please enter a comlab number.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="laboratoryType" class="form-label">Laboratory Type</label>
                        <select id="laboratoryType" class="form-select" name="laboratoryType" required>
                            <option selected="" value="" disabled>Choose...</option>
                            <option value="Computer">Computer</option>
                            <option value="Multimedia">Multimedia</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a laboratory type.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="building" class="form-label">Location</label>
                        <input type="text" class="form-control" name="building" placeholder="ex. COT Building" required>
                        <div class="invalid-feedback">
                            Please provide a location or room building for this comlab.
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