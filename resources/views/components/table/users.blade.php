<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Users</h5>
            <button type="button" class="btn btn-primary" id="exportButton">Export</button>        
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        {{-- hide role and display section code if request is student --}}
                        @if (!request()->is('students') && !request()->is('faculties'))
                            <th scope="col">Role</th>
                        @else
                            @if (!request()->is('faculties'))
                                <th scope="col">Section</th>
                            @endif
                        @endif
                        <th scope="col">College</th>
                        <th scope="col">Department</th>
                        @if (!request()->is('student') && !request()->is('faculties'))
                        @else
                            <th scope="col">Phone</th>
                            <th scope="col">Birthdate</th>
                        @endif
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <a href="{{ route('user.report', ['id' => $user->id]) }}">
                                    {{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name }}
                                </a>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            {{-- hide role and display section code if request is student --}}
                            @if (!request()->is('students') && !request()->is('faculties'))
                                <td>
                                    @switch($user->role)
                                        @case('admin')
                                        @case('dean')

                                        @case('chairperson')
                                            <span class="badge rounded-pill bg-red text-red">{{ $user->role }}</span>
                                        @break

                                        @case('instructor')
                                        @case('part-time instructor')
                                            <span class="badge rounded-pill bg-info-2 text-blue ">{{ $user->role }}</span>
                                        @break

                                        @case('support')
                                            <span class="badge rounded-pill bg-yellow text-yellow ">{{ $user->role }}</span>
                                        @break

                                        @default
                                            <span class="badge rounded-pill bg-gray text-gray">{{ $user->role }}</span>
                                    @endswitch
                                </td>
                            @else
                                @if (!request()->is('faculties'))
                                    <td>
                                        {{ $user->section ? $user->section->sectionCode : 'N/A' }}                                        
                                    </td>
                                @endif
                            @endif
                            <td>{{ $user->college ? $user->college->collegeName : 'N/A' }}</td>
                            <!-- Check if college is null -->
                            <td>{{ $user->department ? $user->department->departmentName : 'N/A' }}</td>
                            <!-- Check if department is null -->
                            @if (!request()->is('student') && !request()->is('faculties'))
                            @else
                                <td>{{ $user->phone ? $user->phone : 'N/A' }}</td>
                                <td>{{ $user->birthdate ? $user->birthdate : 'N/A' }}</td>
                            @endif
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#updateModal{{ $user->id }}">
                                        Edit
                                    </button>
                                    <div class="mx-1"></div>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $user->id }}">
                                        Delete
                                    </button>
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
    let route;
    // Determine the route based on the current user's role
    @if (Request::is('faculties'))
        route = '{{ route('faculty.tableReport') }}';
    @elseif (Request::is('students'))
        route = '{{ route('student.tableReport') }}';
    @else
        route = '{{ route('user.tableReport') }}';
    @endif
   
    // Send an AJAX request to the determined route for report generation
    fetch(route, {
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
        @if (Request::is('faculties'))
            a.download = 'Faculty List Report.pdf';
        @elseif (Request::is('students'))
            a.download = 'Student List Report.pdf';
        @else
            a.download = 'User List Report.pdf';
        @endif
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
