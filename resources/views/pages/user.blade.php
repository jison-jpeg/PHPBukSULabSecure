@extends('layouts.app')

@section('pageTitle')
    @if(Request::is('students'))
        Student Management
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

    <div class="mt-3 mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Add User
        </button>
    </div>

    {{-- Include modal based on route --}}
    @if(Request::is('students'))
        @include('components.modals.student')
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
