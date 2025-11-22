{{-- resources/views/jastiper/detail-pesanan/create.blade.php --}}
@extends('layout.jastiper-app')

@section('title', 'Tambah Detail Pesanan')
@section('page-title', 'Tambah Detail Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
    <div class="form-panel">
        <h2 class="form-title">Tambah Detail Pesanan</h2>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Gunakan nama route sesuai resource 'detail-pesanan' --}}
        <form action="{{ route('jastiper.detail-pesanan.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="form-group">
                <label class="form-label">Pilih Pesanan</label>
                <select name="pesanan_id" id="pesanan_id" class="form-control" required>
                    <option value="">-- Pilih Pesanan --</option>
                    @foreach($pesanans as $p)
                        <option value="{{ $p->id }}" {{ old('pesanan_id') == $p->id ? 'selected' : '' }}>
                            #{{ $p->id }} —
                            {{ \Illuminate\Support\Str::limit($p->alamat_pengiriman ?? ($p->user->name ?? '—'), 40) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Barang</label>
                <select name="barang_id" id="barang_id" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" data-harga="{{ $b->harga ?? 0 }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_barang }} @if(isset($b->harga)) — Rp {{ number_format($b->harga, 0, ',', '.') }} @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" value="{{ old('jumlah', 1) }}"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">Subtotal</label>
                <input type="number" name="subtotal" id="subtotal" step="0.01" class="form-control"
                    value="{{ old('subtotal', 0) }}" readonly>
                <small class="form-text">Subtotal dihitung otomatis: harga barang × jumlah.</small>
            </div>

            <div class="form-actions">
                {{-- Kembali ke daftar pesanan --}}
                <a href="{{ route('jastiper.pesanan.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const barangSelect = document.getElementById('barang_id');
                const jumlahInput = document.getElementById('jumlah');
                const subtotalInput = document.getElementById('subtotal');

                function parseNumber(v) {
                    return v === null || v === '' ? 0 : parseFloat(v);
                }

                function updateSubtotal() {
                    const selected = barangSelect.options[barangSelect.selectedIndex];
                    const harga = parseNumber(selected?.dataset?.harga ?? 0);
                    const jumlah = parseNumber(jumlahInput.value);
                    const subtotal = harga * jumlah;
                    subtotalInput.value = subtotal.toFixed(2);
                }

                barangSelect.addEventListener('change', updateSubtotal);
                jumlahInput.addEventListener('input', updateSubtotal);

                // hitung awal
                updateSubtotal();
            });
        </script>
    @endpush

@endsection