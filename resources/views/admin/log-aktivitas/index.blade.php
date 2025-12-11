@extends('layout.admin-app')


@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">

    <h2 class="user-table-title">Log Aktivitas</h2>

    {{-- SEARCH + TOTAL --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <form method="GET" action="{{ route('admin.log-aktivitas.index') }}"
                  style="display:flex; gap:8px; align-items:center;">

                <input name="q" value="{{ $q ?? '' }}" class="user-search-input"
                       type="text" placeholder="Cari berdasarkan user / aksi / deskripsi"
                       style="width:320px; padding:8px 12px; border-radius:8px; border:1px solid #DDE0E3;">

                <button type="submit" class="btn-search"
                        style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>

            <div style="margin-left:8px; color:#6c7680;">
                Total: <strong>{{ $logs->total() }}</strong>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $l)
                <tr>
                    <td>{{ $l->id }}</td>
                    <td>{{ $l->user?->name ?? 'Tidak Diketahui' }}</td>
                    <td>{{ $l->aksi }}</td>
                    <td>{{ $l->deskripsi }}</td>
                    <td>{{ $l->waktu->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div class="mt-3">
        {{ $logs->links() }}
    </div> --}}

</div>
@endsection
