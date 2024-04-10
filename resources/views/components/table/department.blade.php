<div class="mt-2">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Department Lists</h5>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Department Name</th>
                        <th scope="col">Sections</th>
                        <th scope="col">Description</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $department->departmentName }}</td>
                        <td>{{ $department->sections->count() }}</td>
                        <td>{{ $department->departmentDescription }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateDepartmentModal{{ $department->id }}">
                                    Edit
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteDepartmentModal{{ $department->id }}">
                                    Delete
                                </button>
                                {{-- <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#archiveDepartmentModal">
                                    Archive
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#restoreDepartmentModal">
                                    Restore
                                </button> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->
    </div>
</div>
