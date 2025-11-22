{{-- resources/views/user/items/_form.blade.php --}}
@php
    // $item may be null (create) or model instance (edit)
    $isEdit = isset($item);
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="item-form card">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-row">
        <label for="name">Nama Item</label>
        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" required>
        @error('name')
            <p class="text-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-row">
        <label for="price">Harga (USD)</label>
        <input id="price" name="price" type="number" step="0.01"
            value="{{ old('price', $item->price ?? '') }}" required>
        @error('price')
            <p class="text-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-row">
        <label for="category">Kategori</label>
        <select id="category" name="category">
            <option value="">Pilih kategori</option>
            <option value="burger" {{ old('category', $item->category ?? '') === 'burger' ? 'selected' : '' }}>Burger
            </option>
            <option value="pizza" {{ old('category', $item->category ?? '') === 'pizza' ? 'selected' : '' }}>Pizza
            </option>
            <option value="ramen" {{ old('category', $item->category ?? '') === 'ramen' ? 'selected' : '' }}>Ramen
            </option>
        </select>
    </div>

    <div class="form-row">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" rows="4">{{ old('description', $item->description ?? '') }}</textarea>
    </div>

    <div class="form-row file-row">
        <label for="image">Gambar Item</label>
        <input id="image" name="image" type="file" accept="image/*">
        @if (isset($item) && $item->image)
            <div class="preview">
                <img src="{{ asset('storage/' . $item->image) }}" alt="preview"
                    style="max-width:120px;border-radius:8px;margin-top:8px;">
            </div>
        @endif
        @error('image')
            <p class="text-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-row actions">
        <a href="{{ route('user.items.index') }}" class="btn-muted">Cancel</a>
        <button type="submit" class="btn-primary">{{ $isEdit ? 'Update Item' : 'Create Item' }}</button>
    </div>
</form>
