@extends('layouts.app')
@section('pageTitle', 'Computer Lab Management')
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
        @if (Auth::user()->role === 'admin')
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Add Laboratory
        </button>
        @endif
    </div>
    @include('components.modals.laboratory')

    <section class="section dashboard">
        <div class="row">

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


                {{-- Left side columns --}}
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Computer Laboratories</h5>
                            @include('components.cards.lab')

                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item {{ $laboratories->previousPageUrl() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $laboratories->previousPageUrl() }}" tabindex="-1"
                                            aria-disabled="true">Previous</a>
                                    </li>
                                    @for ($i = 1; $i <= $laboratories->lastPage(); $i++)
                                        <li class="page-item {{ $i == $laboratories->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $laboratories->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ $laboratories->nextPageUrl() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $laboratories->nextPageUrl() }}">Next</a>
                                    </li>
                                </ul>
                            </nav>
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
