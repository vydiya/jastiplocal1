{{-- resources/views/user/items/create.blade.php --}}
@extends('user.layout.app')

@section('title', 'Create Item')
@section('page-title', 'Create Item')

@section('content')
    <div class="container">
        <h3>Create New Item</h3>
        @include('user.items._form', ['action' => route('user.items.store')])
    </div>
@endsection
