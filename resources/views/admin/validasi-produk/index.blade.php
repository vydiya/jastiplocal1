{{-- resources/views/admin/validasi-produk/index.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Validasi Produk')
@section('page-title', 'Validasi Produk')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
<style>
/* tombol teks (tanpa ikon) - ukuran tetap sesuai permintaan */
.btn-text {
  font-family: inherit;
  font-size: 14px;
  line-height: 1;
  color: #4b5563;                /* teks default */
  background: #ffffff;
  border: 1px solid #e6edf3;
  padding: 0;
  border-radius: 10px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;      /* teks tepat di tengah */
  width: 75px;                  /* tetap: 75px */
  height: 36px;                 /* tetap: 36px */
  min-width: 75px;
  box-shadow: none;
  transition: background .12s ease, color .12s ease, transform .06s ease, border-color .12s ease;
  user-select: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}

/* spacing */
.table-actions .btn-text + .btn-text { margin-left: 10px; }

/* hover ringan */
.btn-text:hover { transform: translateY(-1px); }

/* ACTIVE states — warna cerah yang diminta */
.btn-approve.active {
  background: #16a34a; /* hijau cerah */
  color: #ffffff;
  border-color: #16a34a;
  box-shadow: 0 6px 18px rgba(22,163,74,0.16);
}

.btn-reject.active {
  background: #ef4444; /* merah cerah */
  color: #ffffff;
  border-color: #ef4444;
  box-shadow: 0 6px 18px rgba(239,68,68,0.12);
}

/* disabled look while processing */
.btn-text:disabled {
  opacity: 0.65;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

/* pastikan cell operasi punya padding agar tidak nempel ke pinggir */
.table-custom td.col-actions, .table-custom th.col-actions {
  padding-right: 24px;
  text-align: right;
}

/* kecilkan badge (opsional) */
.badge-small { padding:6px 10px; border-radius:12px; font-size:13px; margin-right:8px; }

/* layout actions */
.table-actions { display:inline-flex; gap:8px; align-items:center; justify-content:flex-end; position:relative; z-index:1; }
</style>
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Validasi Produk</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <form method="GET" action="{{ route('admin.validasi-produk.index') }}" style="display:flex; gap:8px; align-items:center;">
                <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text"
                    placeholder="Cari nama / id"
                    style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:360px;">
                <button type="submit" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>

            <div style="margin-left:8px; color:#6c7680;">
                Total: <strong>{{ $validasiProduks->total() ?? $validasiProduks->count() }}</strong>
            </div>
        </div>

        {{-- tombol tambah dihapus --}}
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produk</th>
                    <th>Jastiper</th>
                    {{-- STATUS VALIDASI DIHAPUS DARI TAMPILAN --}}
                    <th>Admin</th>
                    <th>Tanggal Validasi</th>
                    <th class="col-actions">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($validasiProduks as $v)
                <tr id="row-{{ $v->id }}">
                    <td>{{ $v->id }}</td>
                    <td>
                        <div style="display:flex; gap:20px; align-items:center;">
                            @if($v->barang?->foto_barang)
                                <img src="{{ asset('storage/' . $v->barang->foto_barang) }}" alt="thumb"
                                    style="height:40px; width:40px; object-fit:cover; border-radius:6px;">
                            @endif
                            <div style="line-height:1;">
                                <div style="font-weight:600;">{{ $v->barang?->nama_barang ?? '-' }}</div>
                                <div style="font-size:13px; color:#6c7680;">Rp {{ number_format($v->barang?->harga ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $v->barang?->jastiper?->nama_toko ?? ($v->barang?->jastiper?->user?->name ?? '-') }}</td>

                    <td class="admin-col">{{ $v->admin?->name ?? '-' }}</td>
                    <td class="date-col">
                        @if($v->tanggal_validasi)
                            {{ \Carbon\Carbon::parse($v->tanggal_validasi)->format('Y-m-d H:i') }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="col-actions">
                        <div class="table-actions" id="aksi-{{ $v->id }}">
                            {{-- optional badge if you still want small label --}}
                            @if(!empty($v->status_validasi))
                                @if(in_array($v->status_validasi, ['disetujui','approved']))
                                    <span class="badge badge-success badge-small">Disetujui</span>
                                @elseif(in_array($v->status_validasi, ['ditolak','rejected','tolak']))
                                    <span class="badge badge-danger badge-small">Ditolak</span>
                                @endif
                            @endif

                            {{-- tombol teks tanpa ikon (tetap pakai ukuran yang kamu minta) --}}
                            <button type="button"
                                    class="btn-text btn-approve {{ (isset($v->status_validasi) && in_array($v->status_validasi,['disetujui','approved'])) ? 'active' : '' }}"
                                    data-id="{{ $v->id }}"
                                    data-action="setujui"
                                    aria-pressed="{{ (isset($v->status_validasi) && in_array($v->status_validasi,['disetujui','approved'])) ? 'true' : 'false' }}">
                                Setujui
                            </button>

                            <button type="button"
                                    class="btn-text btn-reject {{ (isset($v->status_validasi) && in_array($v->status_validasi,['ditolak','rejected','tolak'])) ? 'active' : '' }}"
                                    data-id="{{ $v->id }}"
                                    data-action="tolak"
                                    aria-pressed="{{ (isset($v->status_validasi) && in_array($v->status_validasi,['ditolak','rejected','tolak'])) ? 'true' : 'false' }}">
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding:20px 0; color:#6c7680;">Tidak ada data validasi produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $validasiProduks->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = '{{ csrf_token() }}';

    async function doAction(id, action, clickedBtn) {
        const container = document.getElementById('aksi-' + id);
        if (!container) return;

        // disable semua tombol di baris
        const btns = container.querySelectorAll('.btn-text');
        btns.forEach(b => b.disabled = true);

        try {
            const res = await fetch("{{ route('admin.validasi-produk.action', ['validasi' => '__id__']) }}".replace('__id__', id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ action: action })
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                alert(data.message ?? 'Gagal memperbarui status');
                return;
            }

            // update admin & tanggal di baris
            const row = document.getElementById('row-' + id);
            if (row) {
                const adminCell = row.querySelector('.admin-col');
                const dateCell = row.querySelector('.date-col');
                if (adminCell) adminCell.innerText = data.admin_name ?? '{{ auth()->user()->name }}';
                if (dateCell) dateCell.innerText = data.tanggal_validasi ?? '-';
            }

            // toggle active class sesuai status dari server
            const aksiWrap = document.getElementById('aksi-' + id);
            if (!aksiWrap) return;
            const approveBtn = aksiWrap.querySelector('[data-action="setujui"]');
            const rejectBtn  = aksiWrap.querySelector('[data-action="tolak"]');

            if (data.status === 'disetujui') {
                approveBtn?.classList.add('active');
                approveBtn?.setAttribute('aria-pressed','true');
                rejectBtn?.classList.remove('active');
                rejectBtn?.setAttribute('aria-pressed','false');
            } else if (data.status === 'ditolak' || data.status === 'rejected') {
                rejectBtn?.classList.add('active');
                rejectBtn?.setAttribute('aria-pressed','true');
                approveBtn?.classList.remove('active');
                approveBtn?.setAttribute('aria-pressed','false');
            } else {
                approveBtn?.classList.remove('active');
                approveBtn?.setAttribute('aria-pressed','false');
                rejectBtn?.classList.remove('active');
                rejectBtn?.setAttribute('aria-pressed','false');
            }

        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan jaringan / server.');
        } finally {
            // enable tombol lagi
            btns.forEach(b => b.disabled = false);
        }
    }

    // delegation handler
    document.body.addEventListener('click', function (e) {
        const el = e.target.closest('.btn-text');
        if (!el) return;
        const id = el.dataset.id;
        const action = el.dataset.action;
        if (!id || !action) return;

        const confirmMsg = action === 'setujui' ? 'Setujui produk ini?' : 'Tolak produk ini?';
        if (!confirm(confirmMsg)) return;

        doAction(id, action, el);
    });
});
</script>
@endpush
