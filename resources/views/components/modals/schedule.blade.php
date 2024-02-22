{{-- Add Schedule Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="" method="POST" novalidate>
                    @csrf
                    <div class="col-md-3">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <select class="form-select" id="position" required="" name="position">
                            <option selected="" disabled="" value="">Choose...</option>
                            <option value="Subject 1">Subject 1</option>
                            <option value="Subject 2">Subject 2</option>
                            <option value="Subject 3">Subject 3</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a subject.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="address" class="form-label">Section Code</label>
                        <input type="text" class="form-control" name="address" placeholder="ex. T321" required>
                        <div class="invalid-feedback">
                            Please enter a section code. <?php echo htmlspecialchars("(ex. T321)"); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="instructor" class="form-label">Instructor</label>
                        <input type="text" class="form-control" name="instructor">
                        <div class="invalid-feedback">
                            Please enter / select an instructor for this subject.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="room" class="form-label">Room</label>
                        <select class="form-select" id="position" required="" name="position" required>
                            <option selected="" disabled="" value="">Choose...</option>
                            <option value="Comlab 1">Comlab 1</option>
                            <option value="Comlab 2">Comlab 2</option>
                            <option value="Comlab 3">Comlab 3</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a room.
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
                                    value="Monday">
                                <label class="form-check-label" for="monday">Monday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="tuesday"
                                    value="Tuesday">
                                <label class="form-check-label" for="tuesday">Tuesday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="wednesday"
                                    value="Wednesday">
                                <label class="form-check-label" for="wednesday">Wednesday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="thursday"
                                    value="Thursday">
                                <label class="form-check-label" for="thursday">Thursday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" id="friday"
                                    value="Friday">
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
