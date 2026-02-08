<form
    action="{{ $unit->exists
        ? route('units.update', $unit->id)
        : route('units.store') }}"
    method="POST"
    id="unitForm"
>
    @csrf
    @if($unit->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {{ $unit->exists ? 'Edit Unit' : 'Tambah Unit' }}
            </h5>
        </div>

        <div class="card-body">

            {{-- FIELD --}}
            <div class="mb-3">
                <label class="form-label">
                    Nama Unit <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $unit->name) }}"
                    required
                    maxlength="50"
                    placeholder="Contoh: Pcs, Kg, Liter, Dus"
                >
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    Nama satuan yang akan digunakan pada produk dan transaksi.
                </small>
            </div>

            {{-- INFO --}}
            <div class="row text-muted small mt-4">
                <div class="col-md-6">
                    <strong>Dibuat:</strong>
                    {{ $unit->exists && $unit->created_at
                        ? $unit->created_at->format('Y-m-d H:i:s')
                        : now()->format('Y-m-d H:i:s') }}
                </div>

                @if($unit->exists)
                    <div class="col-md-6">
                        <strong>Terakhir diupdate:</strong>
                        {{ $unit->updated_at
                            ? $unit->updated_at->format('Y-m-d H:i:s')
                            : '-' }}
                    </div>
                @endif
            </div>

        </div>

        {{-- ACTION (BUTTON KIRI) --}}
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="{{ $unit->exists ? 'save' : 'plus' }}" class="me-1"></i>
                {{ $unit->exists ? 'Simpan Perubahan' : 'Simpan Unit' }}
            </button>

            <a href="{{ route('units.index') }}"
               class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="me-1"></i>
                Kembali
            </a>
        </div>
    </div>
</form>

@vite(['resources/js/pages/units/units-form.js'])

@if($errors->any())
    <script>
        window.serverValidationErrors = {!! json_encode($errors->all()) !!};
    </script>
@endif
