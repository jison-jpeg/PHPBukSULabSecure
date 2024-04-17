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

{{-- View Laboratory Modal --}}
@foreach ($laboratories as $laboratory)
<div class="modal fade" id="viewModal{{ $laboratory->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Comlab {{ $laboratory->roomNumber }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 order-md-2 order-2 mt-lg-4">
                        <div class="card dashboard">
                            <div class="filter">
                              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                  <h6>Filter</h6>
                                </li>
                
                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                              </ul>
                            </div>
                
                            <div class="card-body">
                              <h5 class="card-title">Recent Activity <span>| Today</span></h5>
                
                              <div class="activity">
                
                                <div class="activity-item d-flex">
                                  <div class="activite-label">32 min</div>
                                  <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                                  <div class="activity-content">
                                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                                  </div>
                                </div><!-- End activity item-->
                
                                <div class="activity-item d-flex">
                                  <div class="activite-label">56 min</div>
                                  <i class="bi bi-circle-fill activity-badge text-danger align-self-start"></i>
                                  <div class="activity-content">
                                    Voluptatem blanditiis blanditiis eveniet
                                  </div>
                                </div><!-- End activity item-->
                
                                <div class="activity-item d-flex">
                                  <div class="activite-label">2 hrs</div>
                                  <i class="bi bi-circle-fill activity-badge text-primary align-self-start"></i>
                                  <div class="activity-content">
                                    Voluptates corrupti molestias voluptatem
                                  </div>
                                </div><!-- End activity item-->
                
                                <div class="activity-item d-flex">
                                  <div class="activite-label">1 day</div>
                                  <i class="bi bi-circle-fill activity-badge text-info align-self-start"></i>
                                  <div class="activity-content">
                                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                                  </div>
                                </div><!-- End activity item-->
                
                                <div class="activity-item d-flex">
                                  <div class="activite-label">2 days</div>
                                  <i class="bi bi-circle-fill activity-badge text-warning align-self-start"></i>
                                  <div class="activity-content">
                                    Est sit eum reiciendis exercitationem
                                  </div>
                                </div><!-- End activity item-->
                
                                <div class="activity-item d-flex">
                                  <div class="activite-label">4 weeks</div>
                                  <i class="bi bi-circle-fill activity-badge text-muted align-self-start"></i>
                                  <div class="activity-content">
                                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                                  </div>
                                </div><!-- End activity item-->
                
                              </div>
                
                            </div>
                          </div>
                    </div>
                    <div class="col-lg-6 order-md-1 order-1 mt-4">
                        <div class="card lab-card">
                            <div class="card-body">
                                <div class="card-badge d-flex justify-content-between align-items-center">
                                    <span class="badge rounded-pill bg-{{ $laboratory->occupancyStatus == 'Available' ? 'success' : 'danger' }}">{{ $laboratory->occupancyStatus }}</span>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="toggleSwitch{{ $laboratory->id }}">
                                        <label class="form-check-label" for="toggleSwitch{{ $laboratory->id }}">Lock</label>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-5">
                                    <div class="ps-0 mb-5">
                                        <span class="text-muted small pt-2">{{ $laboratory->laboratoryType }}</span>
                                        <h6>COMLAB {{ $laboratory->roomNumber }}</h6>
                                        <span class="text-muted small pt-2">{{ $laboratory->building }}</span>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <span>{{ $laboratory->label }}:</span>
                                    </div>
                                    <div class="col-12">
                                        <span class="text-muted small">
                                            {{ Str::limit($laboratory->recentUser, 18, '...') }}
                                        </span>
                                    </div>
                                    <div class="col-12">
                                        <span class="text-muted small">
                                            {{ $laboratory->recentTime }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


