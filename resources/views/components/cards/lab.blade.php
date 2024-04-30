<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xxl-5">
    @foreach ($laboratories as $lab)
        <div class="col">
            <div class="card-link">
                <div class="card lab-card">
                    <div class="filter">
                        {{-- If authenticated user is not admin, hide the button --}}
                        @if (Auth::user()->role === 'admin')
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        @endif
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>OPTIONS</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $lab->id }}">Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $lab->id }}">Delete Comlab
                                    {{ $lab->roomNumber }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a class="card-body" data-bs-toggle="modal" data-bs-target="#viewModal{{ $lab->id }}">
                        <div class="card-badge">
                            @if ($lab->lockStatus)
                                <span class="badge rounded-pill bg-danger">Locked</span>
                            @else
                                <span class="badge rounded-pill bg-{{ $lab->occupancyStatus == 'Available' ? 'success' : 'danger' }}">{{ $lab->occupancyStatus }}</span>
                            @endif
                        </div>
                        
                        <div class="d-flex align-items-center ">

                            <div class="ps-0 mb-5">
                                <span class="text-muted small pt-2">{{ $lab->laboratoryType }}</span>
                                <h6>COMLAB {{ $lab->roomNumber }}</h6>
                                <span class="text-muted small pt-2">{{ $lab->building }}</span>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <span class="fw-bold">{{ $lab->label }}:</span>
                            </div>
                            <div class="col-12">
                                <span class="text small">
                                    {{ Str::limit($lab->recentUser, 18, '...') }}
                                </span>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small">
                                    {{ $lab->recentTime }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
