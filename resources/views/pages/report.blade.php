@extends('layouts.app')
@section('pageTitle', 'Reports')
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
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                        <h2>Jayson Tadayca</h2>
                        <h3>Admin</h3>
                        <div class="social-links mt-2">
                            <a href="#" class="twitter"><i class="bi bi-envelope-fill"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

              <div class="card">
                <div class="card-body pt-3">

                    <!-- Bordered Tabs Justified -->
                    <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#bordered-justified-overview" type="button" role="tab"
                                aria-controls="home" aria-selected="true">Overview</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="attendence-tab" data-bs-toggle="tab"
                                data-bs-target="#bordered-justified-attendence" type="button" role="tab"
                                aria-controls="profile" aria-selected="false" tabindex="-1">Attendance</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                        <div class="tab-pane fade show active mt-2" id="bordered-justified-overview" role="tabpanel"
                            aria-labelledby="overview-tab">
                            
                            {{-- @include('components.cards.overview') --}}
                        </div>
                        <div class="tab-pane fade mt-2" id="bordered-justified-attendence" role="tabpanel"
                            aria-labelledby="attendence-tab">
                            
                            {{-- @include('components.table.attendence') --}}
                        </div>
         
                    </div><!-- End Bordered Tabs Justified -->

                </div>
            </div>

            </div>
        </div>
    </section>

@endsection
