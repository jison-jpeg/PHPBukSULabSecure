<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Logs</h5>
            <button type="button" class="btn btn-primary">Export</button>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Room</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                    <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>COMLAB {{ $log->laboratory_id }}</td>
                            <td>{{ $log->user_id }}</td>
                            <td>{{ $log->user->last_name }}, {{ $log->user->first_name }} {{ $log->user->middle_name }}
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->action }}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->
    </div>
</div>