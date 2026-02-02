@php
    $index = $index ?? 0;
    $v = $variant ?? (object)[];
    $rand = $v->id ?? 'new_'.$index;

    $v_images = [];
    if (isset($v->images)) {
        if ($v->images instanceof \Illuminate\Support\Collection) {
            $v_images = $v->images->toArray();
        } elseif (is_array($v->images)) {
            $v_images = $v->images;
        } elseif (is_object($v->images)) {
            $v_images = [(array) $v->images];
        }
    }
@endphp

<div class="variant-row p-3 border rounded mb-3" data-row-id="{{ $rand }}">
    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $v->id ?? '' }}" />

    <div class="row gy-3">

        <!-- IDENTITAS -->
        <div class="col-md-6">
            <label class="form-label">Nama Varian</label>
            <input type="text"
                   name="variants[{{ $index }}][variant_name]"
                   class="form-control"
                   value="{{ old("variants.$index.variant_name", $v->variant_name ?? '') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">SKU</label>
            <input type="text"
                   name="variants[{{ $index }}][sku]"
                   class="form-control"
                   value="{{ old("variants.$index.sku", $v->sku ?? '') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Harga Jual</label>
            <input type="number"
                   name="variants[{{ $index }}][price]"
                   class="form-control"
                   min="0"
                   value="{{ old("variants.$index.price", $v->price ?? 0) }}">
        </div>

        <!-- DIMENSI -->
        <div class="col-md-4">
            <label class="form-label">Panjang (cm)</label>
            <input type="number"
                   step="0.01"
                   name="variants[{{ $index }}][length]"
                   class="form-control"
                   value="{{ old("variants.$index.length", $v->length ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Lebar (cm)</label>
            <input type="number"
                   step="0.01"
                   name="variants[{{ $index }}][width]"
                   class="form-control"
                   value="{{ old("variants.$index.width", $v->width ?? '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Tinggi (cm)</label>
            <input type="number"
                   step="0.01"
                   name="variants[{{ $index }}][height]"
                   class="form-control"
                   value="{{ old("variants.$index.height", $v->height ?? '') }}">
        </div>

        <!-- STATUS -->
        <div class="col-md-3">
            <label class="form-label">Aktif?</label>
            <select name="variants[{{ $index }}][is_active]" class="form-select">
                <option value="1" {{ old("variants.$index.is_active", $v->is_active ?? 1) ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !old("variants.$index.is_active", $v->is_active ?? 1) ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Boleh Dijual?</label>
            <select name="variants[{{ $index }}][is_sellable]" class="form-select">
                <option value="1" {{ old("variants.$index.is_sellable", $v->is_sellable ?? 1) ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !old("variants.$index.is_sellable", $v->is_sellable ?? 1) ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>

        <!-- GAMBAR -->
        <div class="col-md-6">
            <label class="form-label">Gambar Varian</label>
            <input type="file"
                   name="variants[{{ $index }}][images][]"
                   class="form-control"
                   accept="image/*"
                   multiple>
            <small class="text-muted">Maksimal ukuran gambar 3MB (JPG / PNG / WebP)</small>
        </div>

        @if(!empty($v_images))
            <div class="col-md-12 d-flex gap-2 flex-wrap mt-2">
                @foreach($v_images as $img)
                    @php
                        $url = is_array($img) ? $img['url'] ?? null : ($img->url ?? null);
                        $id = is_array($img) ? $img['id'] ?? null : ($img->id ?? null);
                    @endphp
                    @if($url)
                        <div class="position-relative">
                            <img src="{{ asset('storage/'.$url) }}" class="image-thumb" />
                            @if($id)
                                <button type="button"
                                        class="btn btn-sm btn-danger js-delete-image"
                                        data-id="{{ $id }}"
                                        style="position:absolute; top:5px; right:5px;">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- DELETE BUTTON -->
        <div class="col-12 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-variant">
                Hapus Varian
            </button>
        </div>

    </div>
</div>
