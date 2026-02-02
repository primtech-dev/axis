@extends('layouts.vertical', ['title' => '429 - Too Many Requests'])

@section('content')
    <x-error-content
        code="429"
        title="Too Many Requests"
        message="You have made too many requests in a short period of time. Please wait a moment and try again.">

        <div class="alert alert-warning d-inline-block">
            <i class="ti ti-clock me-2"></i>
            Please try again in a few minutes
        </div>
    </x-error-content>
@endsection
