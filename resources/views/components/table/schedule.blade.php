<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Schedule</h5>
            <button type="button" class="btn btn-primary">Export</button>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Schedule</th>
                        <th scope="col">Instructor</th>
                        <th scope="col">Room</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>SIA 2</td>
                        <td>7:30AM - 10:00AM M, TH</td>
                        <td>Mr. Sales Aribe</td>
                        <td>Comlab 2</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateModal">
                                    Edit
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    Delete
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#archiveModal">
                                    Archive
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#restoreModal">
                                    Restore
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->
    </div>
</div>
