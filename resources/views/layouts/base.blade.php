<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- App favicon -->
    <link rel="shortcut icon" href="/images/favicon.ico">

    @include('layouts.shared/head-css')
</head>

<body>

    @yield('content')

    @include('layouts.shared/footer-scripts')

</body>

</html>
