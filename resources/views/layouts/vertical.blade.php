<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Primtech">

    <!-- App favicon -->
    <link rel="shortcut icon" href="/images/favicon.ico">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @vite(['resources/scss/app.scss'])

    @yield('styles')

    @include('layouts.shared/head-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        @include('layouts.shared/menu')

        <div class="content-page">
            <div class="container-fluid">

                @yield('content')
            </div>

            @include('layouts.shared/footer')

        </div>
    </div>

    @include('layouts.shared/customizer')

    <x-toast />

    <x-flash-message />

    @include('layouts.shared/footer-scripts')
    @vite(['resources/js/app.js'])
</body>

</html>
