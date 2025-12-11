@extends('layout.jastiper-app')

@section('title', 'Edit Profil Jastiper')
@section('page-title', 'Edit Profil Saya')

@section('content')
<div class="container">
    <div class="card p-4" style="max-width: 600px; margin: 0 auto;">
        <h2 class="mb-4">Edit Profil Jastiper</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- PENTING: Tambahkan enctype untuk unggah file -->
        <!-- Perhatikan: Route saat ini (jastiper.profile.update) didefinisikan sebagai POST -->
        <form action="{{ route('jastiper.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Hapus @method('PUT') karena rute di web.php adalah Route::post untuk memudahkan file upload --}}

            <!-- Tambahan: Input Foto Profil Toko -->
            <div class="mb-4 text-center">
                @php
                    $profileUrl = $jastiper->profile_toko 
                        ? asset('storage/' . $jastiper->profile_toko) 
                        : 'https://placehold.co/150x150/f0f4f8/999999?text=TOKO';
                @endphp
                <img src="{{ $profileUrl }}" alt="Foto Profil Toko" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 3px solid #ccc;">
                <p class="mb-2">Foto Profil Toko (Max 2MB)</p>
                <input type="file" name="profile_toko" id="profile_toko_input" class="form-control @error('profile_toko') is-invalid @enderror" accept="image/*">
                
                <!-- Server-side validation error message dari Laravel (inilah cara terbaik menampilkan error size) -->
                @error('profile_toko')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

                {{-- @if($jastiper->profile_toko)
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapusFoto">
                        <label class="form-check-label text-danger" for="hapusFoto">
                            Hapus Foto Profil Saat Ini
                        </label>
                    </div>
                @endif --}}
            </div>
            <!-- Akhir Tambahan -->
            
            <div class="mb-3">
                <label class="form-label">Nama Toko</label>
                <input type="text" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" 
                        value="{{ old('nama_toko', $jastiper->nama_toko) }}">
                @error('nama_toko')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
                        value="{{ old('no_hp', $jastiper->no_hp) }}">
                @error('no_hp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jangkauan Layanan</label>
                <input type="text" name="jangkauan" class="form-control @error('jangkauan') is-invalid @enderror" 
                        value="{{ old('jangkauan', $jastiper->jangkauan) }}">
                @error('jangkauan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Dropdown tipe rekening -->
            <div class="mb-3">
                <label class="form-label">Tipe Rekening</label>
                <select name="tipe_rekening" class="form-select @error('tipe_rekening') is-invalid @enderror">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="bank" {{ old('tipe_rekening', $rekening->tipe_rekening ?? '') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="e-wallet" {{ old('tipe_rekening', $rekening->tipe_rekening ?? '') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
                @error('tipe_rekening')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Penyedia</label>
                <input type="text" name="nama_penyedia" class="form-control @error('nama_penyedia') is-invalid @enderror" 
                        value="{{ old('nama_penyedia', $rekening->nama_penyedia ?? '') }}">
                @error('nama_penyedia')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Pemilik</label>
                <input type="text" name="nama_pemilik" class="form-control @error('nama_pemilik') is-invalid @enderror" 
                        value="{{ old('nama_pemilik', $rekening->nama_pemilik ?? '') }}">
                @error('nama_pemilik')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Akun</label>
                <input type="text" name="nomor_akun" class="form-control @error('nomor_akun') is-invalid @enderror" 
                        value="{{ old('nomor_akun', $rekening->nomor_akun ?? '') }}">
                @error('nomor_akun')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('jastiper.profile.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>
@endsection