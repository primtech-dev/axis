@extends('layouts.vertical', ['title' => '503 - Service Unavailable'])

@section('content')
    <x-error-content
        code="503"
        title="Service Unavailable"
        message="We're currently performing maintenance. We'll be back online shortly. Thank you for your patience!"
        :showBackButton="false"
        :showHomeButton="false">

        <div class="alert alert-info d-inline-block">
            <i class="ti ti-tools me-2"></i>
            Scheduled maintenance in progress
        </div>
    </x-error-content>
@endsection
