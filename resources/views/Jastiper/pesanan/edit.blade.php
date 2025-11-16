{{-- resources/views/jastiper/pesanan/edit.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Edit Pesanan')
@section('page-title', 'Edit Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')

<div class="form-panel">

    <h2 class="form-title">Edit Pesanan</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jastiper.pesanan.update', $pesanan->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- User --}}
        <div class="form-group">
            <label class="form-label">Pemilik Pesanan (User)</label>
            <select name="user_id" class="form-control">
                @foreach(\App\Models\User::orderBy('name')->get() as $u)
                    <option value="{{ $u->id }}" {{ $pesanan->user_id == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} — {{ $u->email }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Jastiper --}}
        <div class="form-group">
            <label class="form-label">Jastiper</label>
            <select name="jastiper_id" class="form-control">
                <option value="">-- Tidak ada (opsional) --</option>
                @foreach(\App\Models\Jastiper::orderBy('nama_toko')->get() as $j)
                    <option value="{{ $j->id }}" {{ $pesanan->jastiper_id == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_toko }} — {{ $j->user?->name ?? 'tanpa pemilik' }}
                    </option>
                @endforeach
            </select>
            @error('jastiper_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Tanggal Pesan --}}
        <div class="form-group">
            <label class="form-label">Tanggal Pesan</label>
            <input type="datetime-local" name="tanggal_pesan"
                   class="form-control"
                   value="{{ $pesanan->tanggal_pesan ? $pesanan->tanggal_pesan->format('Y-m-d\TH:i') : '' }}">
            @error('tanggal_pesan') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Total Harga --}}
        <div class="form-group">
            <label class="form-label">Total Harga</label>
            <input type="number" step="0.01" name="total_harga"
                   class="form-control" value="{{ $pesanan->total_harga }}">
            @error('total_harga') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Status Pesanan --}}
        <div class="form-group">
            <label class="form-label">Status Pesanan</label>
            <select name="status_pesanan" class="form-control">
                @foreach(['menunggu','diproses','dikirim','selesai','dibatalkan'] as $status)
                    <option value="{{ $status }}"
                        {{ $pesanan->status_pesanan === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status_pesanan') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="form-group">
            <label class="form-label">Alamat Pengiriman</label>
            <textarea class="form-control" rows="3" name="alamat_pengiriman">{{ $pesanan->alamat_pengiriman }}</textarea>
            @error('alamat_pengiriman') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- No HP --}}
        <div class="form-group">
            <label class="form-label">No. HP</label>
            <input type="text" class="form-control" name="no_hp" value="{{ $pesanan->no_hp }}">
            @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.pesanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>

    </form>
</div>

@endsection
