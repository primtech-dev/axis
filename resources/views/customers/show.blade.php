@extends('layouts.vertical', ['title' => 'Detail Pelanggan'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Pelanggan',
        'subTitle' => 'Detail dan alamat pelanggan',
        'breadcrumbs' => [
            ['name' => 'Pelanggan', 'url' => route('customers.index')],
            ['name' => 'Detail']
        ]
    ])

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                    @can('customers.activate')
                        <button id="btnToggleActive" class="btn btn-sm {{ $customer->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                            {{ $customer->is_active ? 'Non-aktifkan' : 'Aktifkan' }}
                        </button>
                    @endcan
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8">{{ e($customer->full_name) }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ e($customer->email) }}</dd>

                        <dt class="col-sm-4">Telepon</dt>
                        <dd class="col-sm-8">{{ $customer->phone ?? '-' }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8" id="customerStatusText">{{ $customer->is_active ? 'Aktif' : 'Non-aktif' }}</dd>

                        <dt class="col-sm-4">Terdaftar</dt>
                        <dd class="col-sm-8">{{ $customer->created_at ? $customer->created_at->format('d M Y H:i') : '-' }}</dd>

                        <dt class="col-sm-4">Meta</dt>
                        <dd class="col-sm-8"><pre style="white-space:pre-wrap">{{ json_encode($customer->meta ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre></dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Alamat</h5>
                </div>
                <div class="card-body">
                    @if($customer->addresses->isEmpty())
                        <div class="text-muted">Belum ada alamat.</div>
                    @else
                        <div class="list-group">
                            @foreach($customer->addresses as $addr)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ e($addr->label) }} @if($addr->is_default) <span class="badge bg-success">Default</span> @endif</h6>
                                        <small class="text-muted">{{ $addr->created_at ? $addr->created_at->format('d M Y') : '' }}</small>
                                    </div>
                                    <p class="mb-1">{{ e($addr->street) }}, {{ e($addr->city) }}, {{ e($addr->province) }} {{ $addr->postal_code ? e($addr->postal_code) : '' }}</p>
                                    <small class="text-muted">Phone: {{ $addr->phone ?? '-' }} — Country: {{ $addr->country }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('customers.index') }}" class="btn btn-secondary mt-3"><i class="ti ti-arrow-left"></i> Kembali</a>
@endsection

@section('scripts')
    <script>
        window.customerToggleRoute = '{{ route('customers.toggleActive', $customer->id) }}';
        window.customerId = '{{ $customer->id }}';
    </script>
    @vite(['resources/js/pages/customers/show.js'])
@endsection
