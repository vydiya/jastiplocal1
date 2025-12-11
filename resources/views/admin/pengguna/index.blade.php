{{-- resources/views/admin/pengguna/index.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Pengguna - Admin')
@section('page-title', 'Manajemen Pengguna')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
    <style>
        /* rapi sedikit */
        .table-custom td,
        .table-custom th {
            vertical-align: middle !important;
        }

        .btn-action img {
            width: 16px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .table-actions a,
        .table-actions button {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* potong alamat panjang */
        .text-truncate {
            max-width: 320px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .col-id {
            width: 70px;
        }

        .col-username {
            width: 140px;
        }

        .col-nohp {
            width: 120px;
        }

        .col-tgl {
            width: 160px;
        }
    </style>
@endpush

@section('content')

    <div class="user-table-card">
        <h2 class="user-table-title">Data Pengguna</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
                <input id="searchIdInput" class="user-search-input" type="text"
                    placeholder="Cari berdasarkan ID / Username / Nama / Email"
                    style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">

                <button id="btnSearchId" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #007bff; background:#fff; color:#007bff;">
                    Search
                </button>

                <div style="margin-left:12px; color:#6c7680;">
                    Total: <strong>{{ $users->total() ?? $users->count() }}</strong>
                    &nbsp;|&nbsp;
                    Ditampilkan: <strong>{{ $users->count() }}</strong>
                </div>
            </div>

            {{-- <a href="{{ route('admin.pengguna.create') }}" class="btn-add-user"
                style="padding:8px 14px; background:#007bff; color:white; border-radius:8px;">
                + Tambah Pengguna
            </a> --}}
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="usersTable" class="table table-custom">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">Nama</th>
                        <th class="col-namalengkap">Nama Lengkap</th>
                        <th class="col-username">Username</th>
                        <th class="col-email">Email</th>
                        <th class="col-nohp">No. HP</th>
                        <th class="col-alamat">Alamat</th>
                        <th class="col-role">Role</th>
                        <th class="col-tgl">Tanggal Daftar</th>
                        {{-- <th class="col-actions" style="text-align:right">Operasi</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="col-id">{{ $user->id }}</td>
                            <td class="col-name">{{ $user->name }}</td>
                            <td class="col-namalengkap">{{ $user->nama_lengkap ?? '-' }}</td>
                            <td class="col-username">{{ $user->username ?? '-' }}</td>
                            <td class="col-email"><span class="truncate" title="{{ $user->email }}">{{ $user->email }}</span>
                            </td>
                            <td class="col-nohp">{{ $user->no_hp ?? '-' }}</td>
                            <td class="col-alamat"><span class="truncate"
                                    title="{{ $user->alamat }}">{{ $user->alamat ?? '-' }}</span></td>
                            <td class="col-role">{{ $user->role ?? '-' }}</td>
                            <td class="col-tgl">{{ $user->tanggal_daftar ? $user->tanggal_daftar->format('Y-m-d') : '-' }}</td>

                            <!-- Operasi: hanya ICON Edit dan Delete (tanpa teks) -->
                            {{-- <td class="col-actions">
                                <div class="table-actions">
                                    <a href="{{ route('admin.pengguna.edit', $user) }}" class="btn-action edit" title="Edit">
                                        <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                                    </a>

                                    <form action="{{ route('admin.pengguna.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }}?')"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action del" title="Hapus">
                                            <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                        </button>
                                    </form>
                                </div>
                            </td> --}}
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

            // DataTables (tanpa paging karena pakai paginate Laravel)
            $('#usersTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: [8] } // kolom Operasi (index dimulai 0)
                ]
            });

            // Search (by ID, username, name, email)
            function filterRows(query) {
                query = (query || '').toString().toLowerCase().trim();
                if (!query) {
                    $('#usersTable tbody tr').show();
                    return;
                }

                $('#usersTable tbody tr').each(function () {
                    var $tr = $(this);
                    var id = String($tr.data('id') || '').toLowerCase();
                    var username = String($tr.data('username') || '').toLowerCase();
                    var name = String($tr.data('name') || '').toLowerCase();
                    var email = String($tr.data('email') || '').toLowerCase();

                    var matches = id.indexOf(query) !== -1 ||
                        username.indexOf(query) !== -1 ||
                        name.indexOf(query) !== -1 ||
                        email.indexOf(query) !== -1;

                    matches ? $tr.show() : $tr.hide();
                });
            }

            document.getElementById('btnSearchId').addEventListener('click', function () {
                filterRows(document.getElementById('searchIdInput').value);
            });

            document.getElementById('searchIdInput').addEventListener('keyup', function (e) {
                // live filter on type (debounce simple)
                var q = this.value;
                if (this._timer) clearTimeout(this._timer);
                this._timer = setTimeout(function () {
                    filterRows(q);
                }, 200);

                if (e.key === 'Enter') filterRows(this.value);
            });

        });
    </script>
@endpush