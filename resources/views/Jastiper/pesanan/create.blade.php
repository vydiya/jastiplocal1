{{-- resources/views/jastiper/pesanan/create.blade.php --}}
@extends('layout.jastiper-app')

@section('title', 'Tambah Pesanan')
@section('page-title', 'Tambah Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Pesanan</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('jastiper.pesanan.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="form-group">
            <label class="form-label">Pemesan (User)</label>
            <select name="user_id" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected':'' }}>{{ $u->name }} — {{ $u->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Jastiper</label>
            <select name="jastiper_id" class="form-control">
                <option value="">-- Pilih Jastiper (opsional) --</option>
                @foreach($jastipers as $j)
                    <option value="{{ $j->id }}" {{ old('jastiper_id') == $j->id ? 'selected':'' }}>{{ $j->nama_toko }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Total Harga</label>
            <input type="number" name="total_harga" step="0.01" value="{{ old('total_harga', 0) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Status Pesanan</label>
            <select name="status_pesanan" class="form-control">
                <option value="menunggu" {{ old('status_pesanan')=='menunggu' ? 'selected':'' }}>menunggu</option>
                <option value="diproses" {{ old('status_pesanan')=='diproses' ? 'selected':'' }}>diproses</option>
                <option value="dikirim" {{ old('status_pesanan')=='dikirim' ? 'selected':'' }}>dikirim</option>
                <option value="selesai" {{ old('status_pesanan')=='selesai' ? 'selected':'' }}>selesai</option>
                <option value="dibatalkan" {{ old('status_pesanan')=='dibatalkan' ? 'selected':'' }}>dibatalkan</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Pengiriman</label>
            <textarea name="alamat_pengiriman" class="form-control">{{ old('alamat_pengiriman') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">No. HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.pesanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
