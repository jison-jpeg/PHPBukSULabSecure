@extends('layouts.app')
@section('pageTitle', 'Profile')
@section('content')
    <div class="pagetitle">
        <nav>
            <h1>@yield('pageTitle')</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item"><a href="/user">User</a></li>
                <li class="breadcrumb-item active">@yield('pageTitle')</li>
            </ol>
        </nav>
    </div>


    <section class="section profile">
        <div class="row">
          <div class="col-xl-12">
  
            <div class="card">
              <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
  
                <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                <h2>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h2>
                <h3>{{ $user->role }}</h3>
                <div class="social-links mt-2">
                  <a href="#" class="twitter"><i class="bi bi-envelope-fill"></i></a>
                </div>
              </div>
            </div>
  
          </div>
  
          <div class="col-xl-12">
  
            <div class="card">
              <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
  
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview" aria-selected="true" role="tab">Overview</button>
                  </li>
  
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit" aria-selected="false" tabindex="-1" role="tab">Edit Profile</button>
                  </li>
  
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" aria-selected="false" tabindex="-1" role="tab">Change Password</button>
                  </li>
  
                </ul>
                <div class="tab-content pt-2">
  
                  <div class="tab-pane fade show active profile-overview" id="profile-overview" role="tabpanel">  
                    <h5 class="card-title">Profile Details</h5>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Full Name</div>
                      <div class="col-lg-9 col-md-8">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Email</div>
                      <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Username</div>
                      <div class="col-lg-9 col-md-8">{{ $user->username }}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Role</div>
                      <div class="col-lg-9 col-md-8">{{ $user->role }}</div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">College</div>
                      <div class="col-lg-9 col-md-8">{{ $user->college->collegeName ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Department</div>
                      <div class="col-lg-9 col-md-8">{{ $user->department->departmentName ?? 'N/A' }}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Phone</div>
                      <div class="col-lg-9 col-md-8">{{ $user->phone ?? 'N/A' }}</div>
                    </div>
  
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Birthdate</div>
                      <div class="col-lg-9 col-md-8">{{ $user->birthdate ?? 'N/A' }}</div>
                    </div>
  
                  </div>
  
                  <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">
  
                    <!-- Profile Edit Form -->
                    <form method="POST" action="{{ route('profile.put') }}">
                        @csrf
                        @method('PUT')
                      {{-- <div class="row mb-3">
                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                        <div class="col-md-8 col-lg-9">
                          <img src="assets/img/profile-img.jpg" alt="Profile">
                          <div class="pt-2">
                            <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                            <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                          </div>
                        </div>
                      </div> --}}
  
                      <div class="row mb-3">
                        <label for="first_name" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="first_name" type="text" class="form-control" id="first_name" value="{{$user->first_name}}">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="middle_name" class="col-md-4 col-lg-3 col-form-label">Middle Name</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="middle_name" type="text" class="form-control" id="middle_name" value="{{$user->middle_name}}">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="last_name" type="text" class="form-control" id="last_name" value="{{$user->last_name}}">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="username" type="text" class="form-control" id="username" value="{{$user->username}}">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="phone" type="text" class="form-control" id="phone" value="{{$user->phone}}">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="birthdate" class="col-md-4 col-lg-3 col-form-label">Birthdate</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="birthdate" type="date" class="form-control" id="birthdate" value="{{$user->birthdate}}">
                        </div>
                      </div>

                      <div class="text-end mt-5 mb-1">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                    <!-- End Profile Edit Form -->
  
                  </div>
  
                  <div class="tab-pane fade pt-3" id="profile-change-password" role="tabpanel">
                    <!-- Change Password Form -->
                    <form method="POST" action="{{ route('password.put') }}">
                        @csrf
                        @method('PUT')
  
                      <div class="row mb-3">
                        <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="current_password" type="password" class="form-control" id="current_password">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="new_password" type="password" class="form-control" id="new_password">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation">
                        </div>
                      </div>
  
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                      </div>
                    </form>
                    <!-- End Change Password Form -->
  
                  </div>
  
                </div><!-- End Bordered Tabs -->
  
              </div>
            </div>
  
          </div>
        </div>
      </section>
      
@endsection
