@extends('layouts.app')
@section('pageTitle', 'Dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Hello, {{ session('user')->first_name }}!👋</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">@yield('pageTitle')</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    {{-- Dashboard Stats --}}
                    @include('components.stats.dashboard')


                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-12">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                        <div class="activity">
                            @foreach ($logs as $log)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $log->formatted_time_diff }}</div>
                                    <i class='bi bi-circle-fill activity-badge text-{{ $log->action == 'Create' ? 'success' : ($log->action == 'Update' ? 'primary' : 'danger') }} align-self-start'></i>
                                    <div class="activity-content">
                                        {!! nl2br(e($log->description)) !!}
                                    </div>
                                </div>
                            @endforeach




                        </div>

                    </div>
                </div>
                <!-- End Recent Activity -->

            

            </div><!-- End Right side columns -->

        </div>
    </section>

@endsection
