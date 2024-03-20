<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body>

    <div id="preloader">
        <div class="preloader-background"></div>
        <div class="preloader-logo">
            <img src="/assets/img/logo.png" alt="Logo">
        </div>
    </div>

    <!-- ======= Header ======= -->
    @include('layouts.header')

    <!-- Sidebar content -->
    @include('layouts.sidebar')

    <!-- Main content section -->
    <main id="main" class="main">
        @yield('content')
    </main>

    <!-- Footer content -->
    {{-- @include('layouts.footer') --}}


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    {{-- Vendor JS Files --}}
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Display the preloader
            document.getElementById('preloader').style.display = 'block';

            // Hide the preloader immediately
            document.getElementById('preloader').style.opacity = 0;
            setTimeout(function() {
                document.getElementById('preloader').style.display = 'none';
            }, 300);
        });
    </script>

    <!-- Necessary code for autocompletion in tenant admin within add tenant modal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
</body>

</html>
