{{-- Add Laboratory Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Laboratory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="{{ route('laboratories.post') }}" method="POST"
                    novalidate>
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
                        <input type="text" class="form-control" name="building" placeholder="ex. COT Building"
                            required>
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

{{-- Edit Laboratory Modal --}}
@foreach ($laboratories as $laboratory)
    <div class="modal fade" id="editModal{{ $laboratory->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Laboratory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" action="{{ route('laboratories.update', $laboratory->id) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-4">
                            <label for="roomNumber" class="form-label">Comlab No.</label>
                            <input type="number" class="form-control" name="roomNumber"
                                value="{{ $laboratory->roomNumber }}" required>
                            <div class="invalid-feedback">
                                Please enter a comlab number.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="laboratoryType" class="form-label">Laboratory Type</label>
                            <select id="laboratoryType" class="form-select" name="laboratoryType" required>
                                <option selected="" value="" disabled>Choose...</option>
                                <option value="Computer"
                                    {{ $laboratory->laboratoryType === 'Computer' ? 'selected' : '' }}>Computer</option>
                                <option value="Multimedia"
                                    {{ $laboratory->laboratoryType === 'Multimedia' ? 'selected' : '' }}>Multimedia
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a laboratory type.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="building" class="form-label">Location</label>
                            <input type="text" class="form-control" name="building"
                                value="{{ $laboratory->building }}" required>
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
@endforeach

{{-- Delete Laboratory Modal --}}
@foreach ($laboratories as $laboratory)
    <div class="modal fade" id="deleteModal{{ $laboratory->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Laboratory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete <strong>Comlab {{ $laboratory->roomNumber }}</strong> ?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('laboratories.delete', $laboratory->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
