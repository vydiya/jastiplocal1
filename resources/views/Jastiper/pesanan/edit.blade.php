{{-- resources/views/jastiper/pesanan/edit.blade.php --}}
@extends('layout.jastiper-app')

@section('title', 'Edit Pesanan')
@section('page-title', 'Edit Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Pesanan</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jastiper.pesanan.update', $pesanan->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        {{-- Pemilik Pesanan (User) --}}
        <div class="form-group">
            <label class="form-label">Pemilik Pesanan (User)</label>

            @php
                $usersList = isset($users) ? $users : \App\Models\User::orderBy('name')->get();
            @endphp

            <select name="user_id" class="form-control">
                <option value="">-- Pilih User --</option>
                @foreach($usersList as $u)
                    <option value="{{ $u->id }}" {{ (string) old('user_id', $pesanan->user_id) === (string) $u->id ? 'selected' : '' }}>
                        {{ $u->name }} — {{ $u->email }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Jastiper --}}
        <div class="form-group">
            <label class="form-label">Jastiper</label>

            @php
                $jastipersList = isset($jastipers) ? $jastipers : \App\Models\Jastiper::orderBy('nama_toko')->get();
            @endphp

            <select name="jastiper_id" class="form-control">
                <option value="">-- Tidak ada (opsional) --</option>
                @foreach($jastipersList as $j)
                    <option value="{{ $j->id }}" {{ (string) old('jastiper_id', $pesanan->jastiper_id) === (string) $j->id ? 'selected' : '' }}>
                        {{ $j->nama_toko }} — {{ $j->user?->name ?? 'tanpa pemilik' }}
                    </option>
                @endforeach
            </select>
            @error('jastiper_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Tanggal Pesan --}}
        <div class="form-group">
            <label class="form-label">Tanggal Pesan</label>
            <input type="datetime-local" name="tanggal_pesan" class="form-control"
                   value="{{ old('tanggal_pesan', $pesanan->tanggal_pesan ? $pesanan->tanggal_pesan->format('Y-m-d\TH:i') : '') }}">
            @error('tanggal_pesan') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Total Harga --}}
        <div class="form-group">
            <label class="form-label">Total Harga</label>
            <input type="number" step="0.01" name="total_harga" class="form-control"
                   value="{{ old('total_harga', $pesanan->total_harga) }}">
            @error('total_harga') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Status Pesanan --}}
        <div class="form-group">
            <label class="form-label">Status Pesanan</label>
            @php
                $statuses = ['menunggu','diproses','dikirim','selesai','dibatalkan'];
                $currentStatus = old('status_pesanan', $pesanan->status_pesanan);
            @endphp
            <select name="status_pesanan" class="form-control">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $currentStatus === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status_pesanan') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="form-group">
            <label class="form-label">Alamat Pengiriman</label>
            <textarea name="alamat_pengiriman" class="form-control" rows="3" placeholder="Alamat lengkap">{{ old('alamat_pengiriman', $pesanan->alamat_pengiriman) }}</textarea>
            @error('alamat_pengiriman') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- No. HP --}}
        <div class="form-group">
            <label class="form-label">No. HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pesanan->no_hp) }}">
            @error('no_hp') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.pesanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
