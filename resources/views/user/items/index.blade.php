{{-- resources/views/user/items/index.blade.php --}}
@extends('user.layout.app')

@section('title', 'Items')
@section('page-title', 'Items')

@section('content')
    <div class="container">
        <div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3>All Items</h3>
            <a href="{{ route('user.items.create') }}" class="btn-primary">Create Item</a>
        </div>

        <div class="items-grid">
            @if ($items->count())
                <div class="cards-row">
                    @foreach ($items as $item)
                        <div class="card item-card">
                            <div class="thumb">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                        style="width:140px;height:120px;object-fit:cover;border-radius:8px">
                                @else
                                    <div class="no-image"
                                        style="width:140px;height:120px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#aaa">
                                        No Image</div>
                                @endif
                            </div>

                            <div class="meta" style="padding-top:10px">
                                <h4>{{ $item->name }}</h4>
                                <p class="muted">{{ ucfirst($item->category ?? '-') }}</p>
                                <p class="price">${{ number_format($item->price, 2) }}</p>

                                <div class="item-actions" style="margin-top:10px;display:flex;gap:8px;">
                                    <a href="{{ route('user.items.show', $item->id) }}" class="btn-muted small">View</a>
                                    <a href="{{ route('user.items.edit', $item->id) }}" class="btn-primary small">Edit</a>

                                    <form action="{{ route('user.items.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger small">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top:18px">
                    {{ $items->links() }}
                </div>
            @else
                <div class="card">
                    <p>No items found.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
