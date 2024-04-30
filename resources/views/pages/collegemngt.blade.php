@extends('layouts.app')
@section('pageTitle', 'College Management')
@section('content')
    {{-- <div class="pagetitle">
        <nav>
            <h1>@yield('pageTitle')</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item active">@yield('pageTitle')</li>
            </ol>
        </nav>
    </div>

    <div class="mt-3 mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Add Subject
        </button>
    </div> --}}
    @include('components.modals.college')
    @include('components.modals.department')
    @include('components.modals.section')

    <section class="section dashboard">
        <div class="row">

            {{-- Left side columns --}}
            <div class="col-lg-12">

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">@yield('pageTitle')</h5>

                        @if (Auth::user()->role === 'admin')
                            <!-- Bordered Tabs Justified -->
                            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                                <li class="nav-item flex-fill active" role="presentation">
                                    <button class="nav-link w-100 active" id="college-tab" data-bs-toggle="tab"
                                        data-bs-target="#bordered-justified-college" type="button" role="tab"
                                        aria-controls="college" aria-selected="true">College</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="department-tab" data-bs-toggle="tab"
                                        data-bs-target="#bordered-justified-department" type="button" role="tab"
                                        aria-controls="department" aria-selected="false" tabindex="-1">Department</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="section-tab" data-bs-toggle="tab"
                                        data-bs-target="#bordered-justified-section" type="button" role="tab"
                                        aria-controls="section" aria-selected="false" tabindex="-1">Section</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                                <div class="tab-pane fade show active mt-2" id="bordered-justified-college" role="tabpanel"
                                    aria-labelledby="college-tab">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addCollegeModal">
                                        Add College
                                    </button>
                                    @include('components.table.college')
                                </div>
                                <div class="tab-pane fade mt-2" id="bordered-justified-department" role="tabpanel"
                                    aria-labelledby="department-tab">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addDepartmentModal">
                                        Add Department
                                    </button>
                                    @include('components.table.department')
                                </div>
                                <div class="tab-pane fade mt-2" id="bordered-justified-section" role="tabpanel"
                                    aria-labelledby="section-tab">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addSectionModal">
                                        Add Section
                                    </button>
                                    @include('components.table.section')
                                </div>
                            </div>
                            <!-- End Bordered Tabs Justified -->
                        @else
                            <!-- Bordered Tabs Justified -->
                            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                                <li class="nav-item flex-fill active" role="presentation">
                                    <button class="nav-link w-100 active" id="section-tab" data-bs-toggle="tab"
                                        data-bs-target="#bordered-justified-section" type="button" role="tab"
                                        aria-controls="section" aria-selected="true">Section</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                                <div class="tab-pane fade show active mt-2" id="bordered-justified-section" role="tabpanel"
                                    aria-labelledby="section-tab">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addSectionModal">
                                        Add Section
                                    </button>
                                    @include('components.table.section')
                                </div>
                            </div>
                            <!-- End Bordered Tabs Justified -->
                        @endif
                    </div>
                </div>
            </div>

            {{-- </div> --}}
            {{-- End Left side columns --}}

            {{-- Right side columns --}}
            {{-- <div class="col-lg-4">
            </div> --}}
            {{-- End Right side columns --}}


        </div>
    </section>
@endsection
