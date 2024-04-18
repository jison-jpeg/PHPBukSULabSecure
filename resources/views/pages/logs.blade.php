@extends('layouts.app')
@section('pageTitle', isset($user) ? 'Logs of ' . $user->getFullName() : 'Activity Logs')
@section('content')
    <div class="pagetitle">
        <nav>
            <h1>@yield('pageTitle')</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                @if(isset($user))
                    <li class="breadcrumb-item"><a href="{{ route('logs') }}">Logs</a></li>
                    <li class="breadcrumb-item active">{{ $user->getFullName() }}</li>
                @else
                    <li class="breadcrumb-item active">@yield('pageTitle')</li>
                @endif
            </ol>
        </nav>
    </div>

    {{-- @include('components.modals.dormmodal') --}}

    <section class="section dashboard">
        <div class="row">

            {{-- Left side columns --}}
            <div class="col-lg-12">
                @include('components.table.logs')
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
