@extends('layouts.vertical', ['title' => 'Detail Review'])

@section('content')

    @include('layouts.shared.page-title', [
        'title' => 'Detail Review',
        'subTitle' => 'Informasi lengkap & moderasi review pelanggan'
    ])

    <div class="row g-3 mt-1">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Review Content --}}
            <div class="card mb-3">
                <div class="card-body">

                    {{-- Product --}}
                    <h5 class="mb-1">{{ $review->product->name }}</h5>

                    {{-- Variant --}}
                    @if($review->variant)
                        <div class="text-muted small mb-2">
                            Varian: <strong>{{ $review->variant->variant_name }}</strong>
                        </div>
                    @else
                        <div class="text-muted small mb-2">
                            Varian: -
                        </div>
                    @endif

                    {{-- Rating --}}
                    <span class="badge {{ $review->ratingBadgeClass() }}">
                        {{ $review->rating }} ★
                    </span>

                    {{-- Title --}}
                    @if($review->title)
                        <h6 class="mt-3">{{ $review->title }}</h6>
                    @endif

                    {{-- Body --}}
                    <p class="mt-2 mb-0">
                        {{ $review->body }}
                    </p>

                </div>
            </div>

            {{-- Images --}}
            @if($review->images->count())
                <div class="card">
                    <div class="card-header">
                        <strong>Gambar Review</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($review->images->sortBy('position') as $img)
                                <div class="col-6 col-md-3">
                                    <img src="{{ $img->url }}"
                                         class="img-fluid rounded border review-thumb"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imagePreviewModal"
                                         data-image="{{ $img->url }}"
                                         style="cursor:pointer">

                                    <form method="POST"
                                          action="{{ route('admin.review-images.destroy',$img->id) }}"
                                          onsubmit="return confirm('Hapus gambar ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger w-100 mt-1">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- Status --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Status Review</strong>
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('admin.reviews.update-status',$review->id) }}">
                        @csrf @method('PATCH')

                        <select name="status" class="form-select mb-2">
                            @foreach(['published','pending','hidden'] as $s)
                                <option value="{{ $s }}"
                                    @selected($review->status === $s)>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn btn-primary btn-sm w-100">
                            Update Status
                        </button>
                    </form>

                    <hr>

                    @if($review->is_verified_purchase)
                        <span class="badge bg-success w-100">Verified Purchase</span>
                    @else
                        <span class="badge bg-warning text-dark w-100">Unverified Review</span>
                    @endif
                </div>
            </div>

            {{-- Customer --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Customer</strong>
                </div>
                <div class="card-body">
                    <div>{{ $review->customer?->full_name ?? '-' }}</div>
                    <div class="text-muted small">ID: {{ $review->customer_id }}</div>
                </div>
            </div>

            {{-- Order --}}
            @if($review->order)
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Order</strong>
                    </div>
                    <div class="card-body">
                        <div>
                            <a href="{{ url('/admin/orders/'.$review->order->id) }}"
                               class="fw-semibold text-primary text-decoration-underline">
                                {{ $review->order->order_no }}
                            </a>
                        </div>

                        <div class="text-muted small">
                            Status: {{ ucfirst($review->order->status) }}
                        </div>

                        <div class="text-muted small">
                            Total: {{ number_format($review->order->total_amount) }}
                        </div>
                    </div>
                </div>
            @endif


            {{-- Meta --}}
            <div class="card">
                <div class="card-header">
                    <strong>Meta</strong>
                </div>
                <div class="card-body small text-muted">
                    <div>Dibuat: {{ $review->created_at?->format('d M Y H:i') }}</div>
                    <div>Update: {{ $review->updated_at?->format('d M Y H:i') }}</div>
                    <div>Public ID: {{ $review->public_id }}</div>
                </div>
            </div>

        </div>

    </div>

    {{-- IMAGE PREVIEW MODAL --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0 text-center bg-dark">
                    <img id="previewImage"
                         src=""
                         class="img-fluid"
                         style="max-height:90vh">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.review-thumb').forEach(img => {
                img.addEventListener('click', function () {
                    document.getElementById('previewImage').src =
                        this.getAttribute('data-image');
                });
            });
        });
    </script>
@endsection
