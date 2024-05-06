<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Logs</h5>
            <button type="button" class="btn btn-primary" id="exportButton">Export</button>        
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
                            <td>{{ date('F j, Y h:i A', strtotime($log->created_at)) }}</td>
                            <td>{{ $log->laboratory_id ?? 'N/A'}}</td>
                            <td>{{ $log->user_id }}</td>
                            <td>
                                @if ($log->user)
                                <a href="{{ route('logs.byUser', ['userId' => $log->user_id]) }}">
                                    {{ $log->user->last_name }}, {{ $log->user->first_name }} {{ $log->user->middle_name }}
                                </a>
                                @else
                                    User not found
                                @endif
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

<script>
document.getElementById('exportButton').addEventListener('click', function() {
    // Send an AJAX request to the route that generates the report
    fetch('{{ route('logs.tableReport') }}', {
        method: 'GET',
    })
    .then(response => {
        if (response.ok) {
            // If the response is successful, initiate download
            return response.blob();
        } else {
            // Handle error responses
            console.error('Failed to export report');
            // Optionally, display an error message to the user
        }
    })
    .then(blob => {
        // Create a URL for the blob
        const url = window.URL.createObjectURL(blob);
        // Create a link element
        const a = document.createElement('a');
        // Set the href attribute to the blob URL
        a.href = url;
        // Set the download attribute to specify the file name
        a.download = 'Audit Logs Report.pdf'; // Change the file name as needed
        // Append the link to the document body
        document.body.appendChild(a);
        // Click the link to initiate download
        a.click();
        // Remove the link from the document body
        document.body.removeChild(a);
        // Revoke the blob URL to free up memory
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error exporting report:', error);
        // Optionally, display an error message to the user
    });
});
</script>