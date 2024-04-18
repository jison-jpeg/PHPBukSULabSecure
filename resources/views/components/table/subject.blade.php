<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Subject</h5>
            <button type="button" class="btn btn-primary" id="exportButton">Export</button>        
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Subject Code</th>
                        <th scope="col">Description</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $subject)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $subject->subjectName }}</td>
                        <td>{{ $subject->subjectCode }}</td>
                        <td>{{ $subject->subjectDescription }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#updateModal{{ $subject->id }}">
                                    Edit
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $subject->id }}">
                                    Delete
                                </button>
                                {{-- <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#archiveModal">
                                    Archive
                                </button>
                                <div class="mx-1"></div>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#restoreModal">
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

<script>
document.getElementById('exportButton').addEventListener('click', function() {
    // Send an AJAX request to the route that generates the report
    fetch('{{ route('subject.tableReport') }}', {
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
        a.download = 'Subject List Report.pdf'; // Change the file name as needed
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
