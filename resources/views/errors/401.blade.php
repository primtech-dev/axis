@extends('layouts.vertical', ['title' => '401 - Unauthorized'])

@section('content')
    <x-error-content
        code="401"
        title="Unauthorized"
        message="You need to be authenticated to access this page. Please log in to continue."
        :showBackButton="false"
        :showHomeButton="false">

        <div class="mt-3">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="ti ti-login me-1"></i> Go to Login
            </a>
        </div>
    </x-error-content>
@endsection
