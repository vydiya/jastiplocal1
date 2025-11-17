{{-- resources/views/user/items/show.blade.php --}}
@extends('user.layout.app')

@section('title', $item->name ?? 'Item')
@section('page-title', $item->name ?? 'Item')

@section('content')
    <div class="container">
        <div class="card" style="display:flex;gap:22px;align-items:flex-start;">
            <div style="flex:0 0 260px;">
                @if ($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                        style="width:100%;height:auto;border-radius:8px;">
                @else
                    <div
                        style="width:100%;height:200px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#aaa">
                        No image</div>
                @endif
            </div>

            <div style="flex:1;">
                <h3>{{ $item->name }}</h3>
                <p class="muted">Category: {{ ucfirst($item->category ?? '–') }}</p>
                <p class="price" style="font-size:20px;font-weight:700;margin-top:6px;">
                    ${{ number_format($item->price, 2) }}</p>

                <div style="margin-top:14px;">
                    <h4>Description</h4>
                    <p>{{ $item->description ?? 'No description.' }}</p>
                </div>

                <div style="margin-top:18px;display:flex;gap:10px;">
                    <a href="{{ route('user.items.edit', $item->id) }}" class="btn-primary">Edit</a>
                    <a href="{{ route('user.items.index') }}" class="btn-muted">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
