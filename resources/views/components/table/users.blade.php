<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Users</h5>
            <button type="button" class="btn btn-primary">Export</button>
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
                        @if (!request()->is('student') && !request()->is('faculties'))
                            <th scope="col">Role</th>
                        @else
                            <th scope="col">Section Code</th>
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
                            <td>{{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name }}</td>
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
                                <td>{{ $user->section_code ? $user->section_code : 'N/A' }}</td>
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
