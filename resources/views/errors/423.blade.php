@extends('layouts.vertical', ['title' => '423 - Data Locked'])

@section('content')
    <x-error-content
        code="423"
        title="Data Locked"
        message="The data you are trying to access is locked. This data cannot be modified or deleted because it is in a locked status.">

        <p class="text-muted fs-sm">
            Error code: <span class="fw-semibold">423</span>
        </p>

        <div class="mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-primary">
                <i class="mdi mdi-arrow-left me-1"></i> Go Back
            </a>
        </div>
    </x-error-content>
@endsection
