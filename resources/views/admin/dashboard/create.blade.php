{{-- resources/views/admin/dashboard/create.blade.php --}}
@extends('layout.admin-app')


@section('title','Create Item')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>Tambah Data</strong></div>
                <div class="card-body">
                    <form action="{{ route('admin.dashboard.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>
                        <button class="btn btn-success" type="submit">Simpan</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
