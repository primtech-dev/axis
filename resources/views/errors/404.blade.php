@extends('layouts.vertical', ['title' => '404 - Page Not Found'])

@section('content')
    <x-error-content
        code="404"
        title="Page Not Found"
        message="Oops! The page you are looking for does not exist. It might have been moved or deleted.">

        <p class="text-muted fs-sm">
            Error code: <span class="fw-semibold">404</span>
        </p>
    </x-error-content>
@endsection
