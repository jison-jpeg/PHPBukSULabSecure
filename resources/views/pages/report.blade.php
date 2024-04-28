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

    <section class="section dashboard">
        <div class="row">

            <div class="col-lg-4">
                <div class="card section profile ">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle ">
                        <h2 class="pt-4">{{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name }}</h2>
                        <h5>
                            <span class="badge rounded-pill bg-primary">
                                {{ $user->role }}
                            </span>
                            
                        </h5>
                        <div class="social-links mt-2">
                            <a href="#" class="email"><i class="bi bi-envelope-fill"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">

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

                    <div class="card-body pb-0">
                        <h5 class="card-title">Attendance Overview <span>| Today</span></h5>

                        <div id="donutChart" style="min-height: 350px;" class="echart"></div>


                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                echarts.init(document.querySelector("#donutChart")).setOption({
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    legend: {
                                        top: '5%',
                                        left: 'center'
                                    },
                                    series: [{
                                        name: 'Access From',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        avoidLabelOverlap: false,
                                        label: {
                                            show: false,
                                            position: 'center'
                                        },
                                        emphasis: {
                                            label: {
                                                show: true,
                                                fontSize: '18',
                                                fontWeight: 'bold'
                                            }
                                        },
                                        labelLine: {
                                            show: false
                                        },
                                        data: [{
                                                value: {{ $presentCount }},
                                                name: 'Present'
                                            },
                                            {
                                                value: {{ $lateCount }},
                                                name: 'Late'
                                            },
                                            {
                                                value: {{ $incompleteCount }},
                                                name: 'Incomplete'
                                            },
                                            {
                                                value: {{ $absentCount }},
                                                name: 'Absent'
                                            },
                                        ]
                                    }]
                                });
                            });
                        </script>

                    </div>
                </div><!-- End Website Traffic -->



            </div>

        </div>
    </section>


    <div class="col-xl-12">

        <div class="card">
            <div class="card-body pt-3">

                <!-- Bordered Tabs Justified -->
                <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                    <li class="nav-item flex-fill" role="presentation">
                        <button class="nav-link w-100 active" id="overview-tab" data-bs-toggle="tab"
                            data-bs-target="#bordered-justified-overview" type="button" role="tab" aria-controls="home"
                            aria-selected="true">Overview</button>
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
                        @include('components.cards.overview')
                    </div>
                    <div class="tab-pane fade mt-2" id="bordered-justified-attendence" role="tabpanel"
                        aria-labelledby="attendence-tab">

                        @include('components.table.report')
                    </div>

                </div><!-- End Bordered Tabs Justified -->

            </div>
        </div>
    </div>
@endsection
