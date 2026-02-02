@extends('layouts.vertical', ['title' => '403 - Access Forbidden'])

@section('content')
    <x-error-content
        code="403"
        title="Access Forbidden"
        message="Sorry, you don't have permission to access this resource. Please contact your administrator if you believe this is a mistake.">

        <div class="alert alert-warning d-inline-block">
            <i class="ti ti-shield-lock me-2"></i>
            You need proper authorization to view this page
        </div>
    </x-error-content>
@endsection
