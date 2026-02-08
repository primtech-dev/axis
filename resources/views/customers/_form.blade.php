<style>
    .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
</style>

<form
    action="{{ $customer->exists
        ? route('customers.update', $customer->id)
        : route('customers.store') }}"
    method="POST"
    id="customerForm"
>
    @csrf
    @if($customer->exists)
        @method('PUT')
    @endif

    <div class="row">
        {{-- LEFT --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Customer</h5>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Kode Customer <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="code"
                               class="form-control"
                               value="{{ old('code', $customer->code) }}"
                               placeholder="CUST-001"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Customer <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $customer->name) }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $customer->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address"
                                  class="form-control"
                                  rows="3">{{ old('address', $customer->address) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            <div class="card mb-3 card-help">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status</h5>
                </div>
                <div class="card-body">

                    <div class="form-check mb-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               class="form-check-input"
                            {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Customer Aktif
                        </label>
                    </div>

                    <small class="text-muted">
                        Customer non-aktif tidak akan muncul di transaksi.
                    </small>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="{{ $customer->exists ? 'save' : 'plus' }}" class="me-1"></i>
                            {{ $customer->exists ? 'Simpan Perubahan' : 'Simpan Customer' }}
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                            <i data-lucide="arrow-left" class="me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@vite(['resources/js/pages/customers/customers-form.js'])

@if($errors->any())
    <script>
        window.serverValidationErrors = {!! json_encode($errors->all()) !!};
    </script>
@endif
