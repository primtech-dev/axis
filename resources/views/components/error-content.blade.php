@props([
    'code' => '404',
    'title' => 'Page Not Found',
    'message' => 'The page you are looking for does not exist.',
    'showHomeButton' => true,
    'showBackButton' => true
])

<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px);">
    <div class="row justify-content-center w-100">
        <div class="col-lg-6 col-xl-5">
            <div class="text-center">
                {{-- Error Code --}}
                <div class="text-error-number mb-4">
                    <h1 class="display-1 fw-bold text-primary" style="font-size: 8rem;">{{ $code }}</h1>
                </div>

                {{-- Title --}}
                <h3 class="mb-3">{{ $title }}</h3>

                {{-- Message --}}
                <p class="text-muted mb-4">{{ $message }}</p>

                {{-- Custom Slot --}}
                @if($slot->isNotEmpty())
                    <div class="mb-4">
                        {{ $slot }}
                    </div>
                @endif

                {{-- Buttons --}}
                <div class="d-flex gap-2 justify-content-center">
                    @if($showBackButton)
                        <button onclick="window.history.back()" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Go Back
                        </button>
                    @endif

                    @if($showHomeButton)
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="ti ti-home me-1"></i> Back to Home
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-error-number h1 {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    @media (max-width: 576px) {
        .text-error-number h1 {
            font-size: 5rem !important;
        }
    }
</style>
