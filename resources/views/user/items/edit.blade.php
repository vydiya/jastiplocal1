{{-- resources/views/user/items/edit.blade.php --}}
@extends('user.layout.app')

@section('title', 'Edit Item')
@section('page-title', 'Edit Item')

@section('content')
    <div class="container">
        <h3>Edit Item</h3>
        @include('user.items._form', [
            'action' => route('user.items.update', $item->id),
            'item' => $item,
        ])
    </div>
@endsection
