@if(!empty($title))
    <div class="row mb-4 pt-3">
        <div class="col-12">
            {{-- Breadcrumb --}}
            @if(!empty($breadcrumbs))
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @foreach($breadcrumbs as $breadcrumb)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] ?? '#' }}">{{ $breadcrumb['name'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif

            {{-- Title & Subtitle --}}
            <h4 class="mb-1">{{ $title }}</h4>
            @if(!empty($subTitle))
                <p class="text-muted mb-0">{{ $subTitle }}</p>
            @endif
        </div>
    </div>
@endif
