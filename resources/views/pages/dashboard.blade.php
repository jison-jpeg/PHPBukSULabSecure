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
                    @include('components.stats.instructor')
                    @include('components.stats.recentactivity')


                </div>
            </div>
            <!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                {{-- @include('components.stats.recentactivity') --}}


            

            </div><!-- End Right side columns -->

        </div>
    </section>

@endsection
