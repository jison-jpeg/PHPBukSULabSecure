@extends('layouts.app')

@section('pageTitle')
    @if (Request::is('students'))
        Student Management
    @elseif (Request::is('faculties'))
        Faculty Management
    @else
        User Management
    @endif
@endsection

@section('content')
    <div class="pagetitle">
        <nav>
            <h1>@yield('pageTitle')</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item active">@yield('pageTitle')</li>
            </ol>
        </nav>
    </div>

    @if (Request::is('students'))
        @if (Auth::user()->role === 'admin')
            <div class="mt-3 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Student
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import Students
                </button>
            </div>
        @endif
    @elseif (Request::is('faculties'))
        @if (Auth::user()->role === 'admin')
            <div class="mt-3 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Faculty
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import Faculties
                </button>
            </div>
        @endif
    @else
        @if (Auth::user()->role === 'admin')
            <div class="mt-3 mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add User
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import Users
                </button>
            </div>
        @endif
    @endif



    {{-- Include modal based on route --}}
    @if (Request::is('students'))
        @include('components.modals.student')
    @elseif (Request::is('faculties'))
        @include('components.modals.faculty')
    @else
        @include('components.modals.user')
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    <section class="section dashboard">
        <div class="row">
            {{-- Left side columns --}}
            <div class="col-lg-12">
                @include('components.table.users')
            </div>
        </div>
    </section>
@endsection
