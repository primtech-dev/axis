@extends('layouts.vertical', ['title' => '500 - Internal Server Error'])

@section('content')
    <x-error-content
        code="500"
        title="Internal Server Error"
        message="Whoops, something went wrong on our servers. We're working to fix the problem. Please try again later."
        :showBackButton="false">

        <div class="alert alert-danger d-inline-block">
            <i class="ti ti-alert-triangle me-2"></i>
            Our team has been notified about this issue
        </div>
    </x-error-content>
@endsection
