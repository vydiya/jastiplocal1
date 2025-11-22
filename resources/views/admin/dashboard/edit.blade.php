{{-- resources/views/admin/dashboard/edit.blade.php --}}
@extends('layout.admin-app')


@section('title','Edit Item')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>Edit Data</strong></div>
                <div class="card-body">
                    <form action="{{ route('admin.dashboard.update', $id ?? 0) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" value="{{ $item->name ?? '' }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4">{{ $item->description ?? '' }}</textarea>
                        </div>
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
