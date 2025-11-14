{{-- resources/views/admin/pengguna/index.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Pengguna - Admin')
@section('page-title', 'Manajemen Pengguna')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
    <style>
        /* tambahkan sedikit rapi */
        .table-custom td,
        .table-custom th {
            vertical-align: middle !important;
        }

        .btn-action img {
            width: 16px;
            margin-right: 4px;
            vertical-align: middle;
        }

        .table-actions a,
        .table-actions button {
            display: flex;
            align-items: center;
            gap: 4px;
        }
    </style>
@endpush

@section('content')

    <div class="user-table-card">
        {{-- JUDUL HALAMAN --}}
        <h2 class="user-table-title">Data Pengguna</h2>

        {{-- 🔍 SEARCH + TOTAL + ADD BUTTON --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
                <input id="searchIdInput" class="user-search-input" type="text" placeholder="Cari berdasarkan ID"
                    style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px;">

                <button id="btnSearchId" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #007bff; background:#fff; color:#007bff;">
                    Search
                </button>

                <div style="margin-left:8px; color:#6c7680;">
                    Total: <strong>{{ $users->total() ?? $users->count() }}</strong>
                </div>
            </div>

            <a href="{{ route('admin.pengguna.create') }}" class="btn-add-user"
                style="padding:8px 14px; background:#007bff; color:white; border-radius:8px;">
                + Tambah Pengguna
            </a>
        </div>

        {{-- 🧾 TABLE --}}
        <div class="table-responsive">
            <table id="usersTable" class="table table-custom">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="text-align:right; width:200px">Operasi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr data-id="{{ $user->id }}">
                            <td class="col-id">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role ?? '-' }}</td>

                            <td class="table-actions" style="text-align:right;">
                                <div style="display:flex; justify-content:flex-end; gap:10px;">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('admin.pengguna.show', $user->id) }}" class="btn-action" title="Detail">
                                        <img src="{{ asset('admin/assets/images/icons/lihat.svg') }}">
                                        Detail
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn-action" title="Edit">
                                        <img src="{{ asset('admin/assets/images/icons/edit.svg') }}">
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action del" title="Hapus"
                                            onclick="return confirm('Hapus pengguna ini?')">
                                            <img src="{{ asset('admin/assets/images/icons/delete.svg') }}">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $users->links() }}
        </div>

    </div>
@endsection


@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // DataTables (tanpa search & pagination)
            $('#usersTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: [4] }
                ]
            });

            // Search by ID
            function filterById(id) {
                id = id.toString().trim();
                if (!id) {
                    $('#usersTable tbody tr').show();
                    return;
                }
                $('#usersTable tbody tr').each(function () {
                    var rowId = $(this).data('id')?.toString() ?? '';
                    (rowId.indexOf(id) !== -1) ? $(this).show() : $(this).hide();
                });
            }

            document.getElementById('btnSearchId').addEventListener('click', function () {
                filterById(document.getElementById('searchIdInput').value);
            });

            document.getElementById('searchIdInput').addEventListener('keyup', function (e) {
                if (e.key === 'Enter') filterById(this.value);
            });

        });
    </script>
@endpush