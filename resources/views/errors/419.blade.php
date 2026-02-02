@extends('layouts.vertical', ['title' => '419 - Page Expired'])

@section('content')
    <x-error-content
        code="419"
        title="Page Expired"
        message="Your session has expired. Please refresh the page and try again."
        :showBackButton="false"
        :showHomeButton="false">

        <div class="mt-3">
            <button onclick="window.location.reload()" class="btn btn-primary">
                <i class="ti ti-refresh me-1"></i> Refresh Page
            </button>
        </div>
    </x-error-content>
@endsection
