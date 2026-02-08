<form
    action="{{ $category->exists
        ? route('categories.update', $category->id)
        : route('categories.store') }}"
    method="POST"
    id="categoryForm"
>
    @csrf
    @if($category->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {{ $category->exists ? 'Edit Kategori' : 'Tambah Kategori' }}
            </h5>
        </div>

        <div class="card-body">

            {{-- FIELD --}}
            <div class="mb-3">
                <label class="form-label">
                    Nama Kategori <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $category->name) }}"
                    required
                    maxlength="100"
                    placeholder="Contoh: Elektronik, Pakaian, Aksesoris"
                >
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    Gunakan nama yang singkat, jelas, dan mudah dipahami.
                </small>
            </div>

            {{-- INFO --}}
            <div class="row text-muted small mt-4">
                <div class="col-md-6">
                    <strong>Dibuat:</strong>
                    {{ $category->exists && $category->created_at
                        ? $category->created_at->format('Y-m-d H:i:s')
                        : now()->format('Y-m-d H:i:s') }}
                </div>

                @if($category->exists)
                    <div class="col-md-6">
                        <strong>Terakhir diupdate:</strong>
                        {{ $category->updated_at
                            ? $category->updated_at->format('Y-m-d H:i:s')
                            : '-' }}
                    </div>
                @endif
            </div>

        </div>

        {{-- ACTION (BUTTON KIRI) --}}
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="{{ $category->exists ? 'save' : 'plus' }}" class="me-1"></i>
                {{ $category->exists ? 'Simpan Perubahan' : 'Simpan Kategori' }}
            </button>

            <a href="{{ route('categories.index') }}"
               class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="me-1"></i>
                Kembali
            </a>
        </div>
    </div>
</form>

@vite(['resources/js/pages/categories/categories-form.js'])

@if($errors->any())
    <script>
        window.serverValidationErrors = {!! json_encode($errors->all()) !!};
    </script>
@endif
