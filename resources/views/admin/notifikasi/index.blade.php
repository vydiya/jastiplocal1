@extends('layout.admin-app')

@section('title', 'Notifikasi Saya')
@section('page-title', 'Notifikasi Pribadi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">

    <style>
        /* Badge notifikasi */
        .notif-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-belum { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .badge-sudah { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* Baris belum dibaca */
        tr.notif-unread {
            font-weight: 600;
            background-color: #f8fbfd !important;
        }

        /* Tombol aksi kecil – SERAGAM */
        .btn-action-sm {
            padding: 6px 14px !important;
            border-radius: 6px !important;
            font-size: 0.85rem !important;
            font-weight: 600;
            line-height: 1 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
        }

        /* Tombol Sudah */
        .btn-sudah {
            background: #27ae60;
            color: white;
        }

        /* Tombol Hapus */
        .btn-hapus {
            background: #e74c3c;
            color: white;
        }

        /* Filter */
        .filter-control {
            padding: 10px 14px;
            border: 1px solid #DDE0E3;
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
<div class="user-table-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="user-table-title">
            Notifikasi Saya 
            @if($belum_baca_count > 0)
                <span style="font-size:0.9rem; color:#e67e22; font-weight:600;">
                    ({{ $belum_baca_count }} belum dibaca)
                </span>
            @endif
        </h2>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="user-controls" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">

            <form method="GET" action="{{ url()->current() }}" id="formFilter">
                <select name="status" class="filter-control" onchange="this.form.submit()" style="width:180px;">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="belum_baca" {{ $status === 'belum_baca' ? 'selected' : '' }}>
                        Belum Dibaca ({{ $belum_baca_count }})
                    </option>
                    <option value="sudah_baca" {{ $status === 'sudah_baca' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>

                <input name="search" value="{{ request('search') }}" type="text"
                       placeholder="Cari pesan atau ID pesanan..."
                       class="filter-control" style="width:320px;">

                <button id="btnSearchJastiper" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>

            <div style="color:#6c7680; white-space:nowrap;">
                Total: <strong>{{ $notifikasis->total() }}</strong>
            </div>
        </div>

        @if($belum_baca_count > 0)
            <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-add-user"
                        style="padding:10px 18px; background:#27ae60; color:white; border-radius:8px; font-size:0.9rem;">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Jenis</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($notifikasis as $n)
                @php
                    $data    = is_string($n->data) ? json_decode($n->data, true) : $n->data;
                    $isRead  = $n->read_at !== null;
                @endphp

                <tr class="{{ $isRead ? '' : 'notif-unread' }}">
                    <td>{{ $data['pesanan_id'] ?? '-' }}</td>

                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $data['jenis_notifikasi'] ?? Str::afterLast($n->type, '\\') }}
                        </span>
                    </td>

                    <td style="line-height:1.5;">
                        {{ $data['pesan'] ?? 'Tidak ada pesan' }}
                    </td>

                    <td>
                        <span class="notif-badge {{ $isRead ? 'badge-sudah' : 'badge-belum' }}">
                            {{ $isRead ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                        </span>
                    </td>

                    <td>{{ $n->created_at->format('d M Y H:i') }}</td>

                    <td>
                        <div class="table-actions" style="display:flex; justify-content:center; gap:6px;">

                            {{-- Tombol Sudah --}}
                            @if(!$isRead)
                            <form action="{{ route('notifikasi.markAsRead', $n->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit"
                                        class="btn-action-sm btn-sudah"
                                        title="Tandai Sudah Dibaca">
                                    Sudah
                                </button>
                            </form>
                            @endif

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('notifikasi.destroy', $n->id) }}" method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Yakin menghapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-action-sm btn-hapus"
                                        title="Hapus">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px 10px; color:#6c7680; font-size:1rem;">
                        Tidak ada notifikasi untuk Anda.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-4">
        {{ $notifikasis->appends(request()->query())->links() }}
    </div>

</div>
@endsection
