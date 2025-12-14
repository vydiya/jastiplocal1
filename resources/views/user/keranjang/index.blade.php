@php 
    use Illuminate\Support\Str;
    $userIsLoggedIn = Auth::check();
    $userName = Auth::user()->name ?? 'Pengguna';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Keranjang Belanja - JASTGO</title>
    @include('user.layout.style')

    <style>
        /* CSS KHUSUS KERANJANG */
        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .cart-items {
            flex: 3;
            min-width: 550px;
        }

        .cart-summary {
            flex: 1;
            min-width: 300px;
            max-height: 350px;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 100px;
        }

        .item-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            gap: 1rem;
            align-items: center;
            transition: border 0.2s;
            border: 1px solid transparent;
        }
        
        .item-card.selected {
            border: 1px solid #006FFF;
            background-color: #f0f8ff;
        }

        .item-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #006FFF;
            margin-right: 0.5rem;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f0f0f0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0.25rem;
            display: block;
            text-decoration: none;
        }

        .item-store {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .item-price {
            font-weight: 700;
            color: #006FFF;
            font-size: 1.2rem;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .quantity-control button {
            background: #C1E0F4;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
        }

        .quantity-control input {
            width: 40px;
            text-align: center;
            border: 1px solid #C1E0F4;
            padding: 0.4rem 0;
            border-radius: 6px;
        }

        .remove-btn {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
            font-size: 1rem;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .remove-btn:hover {
            opacity: 1;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            color: #4b5563;
        }

        .summary-total {
            border-top: 1px solid #eee;
            padding-top: 1rem;
            margin-top: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #006FFF;
        }

        .btn-checkout {
            background: #006FFF;
            color: white !important;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            display: block;
            margin-top: 1.5rem;
            border: none;
            width: 100%;
            cursor: pointer;
        }
        
        .btn-checkout:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
@include('user.layout.header', [
    'isLoggedIn' => $userIsLoggedIn,
    'cartCount' => $cartCount,
    'searchValue' => '',
    'userName' => $userName,
])

<div class="cart-container">
    <div class="cart-items">
        <h2 class="section-title" style="margin-bottom: 1.5rem; padding-top: 1rem;">
            Keranjang Belanja Anda ({{ $cartCount }} Item)
        </h2>

        <div style="margin-bottom: 1rem; padding-left: 0.5rem;">
            <label style="display:flex;align-items:center;font-weight:600;">
                <input type="checkbox" id="select-all" class="item-checkbox">
                Pilih Semua
            </label>
        </div>

        @forelse ($cartDetails as $item)
            <div class="item-card" id="card-{{ $item->produk->id }}">
                <input type="checkbox" 
                    name="selected_products[]" 
                    value="{{ $item->produk->id }}" 
                    class="item-checkbox product-checkbox"
                    form="checkout-form"
                    data-price="{{ $item->total_harga }}"
                    data-weight="{{ ($item->produk->berat_gr ?? 1) * $item->qty }}"
                    data-store-id="{{ $item->produk->jastiper_id }}"> {{-- [TAMBAHAN] --}}

                <div class="item-image">
                    <img src="{{ asset('storage/' . $item->produk->foto_barang) }}">
                </div>

                <div class="item-details">
                    <a href="{{ route('produk.detail', $item->produk->id) }}" class="item-name">
                        {{ $item->produk->nama_barang }}
                    </a>
                    <div class="item-store">{{ $item->produk->jastiper->nama_toko }}</div>
                    <div class="item-store">Lokasi: {{ $item->produk->jastiper->jangkauan }}</div>
                </div>

                <div class="item-price">
                    Rp {{ number_format($item->total_harga,0,',','.') }}
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <div class="cart-summary">
        <form id="checkout-form" action="{{ route('checkout.prepare') }}" method="POST">
            @csrf

            <div class="summary-row">
                <span>Total</span>
                <span id="summary-total">Rp 0</span>
            </div>

            <button type="submit" class="btn-checkout" id="btn-checkout" disabled>
                Checkout (<span id="count-selected">0</span>)
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const checkboxes = document.querySelectorAll('.product-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl = document.getElementById('summary-total');
    const weightEl = document.getElementById('summary-weight');
    const countEl = document.getElementById('count-selected');
    const btnCheckout = document.getElementById('btn-checkout');

    let selectedStoreId = null; // [TAMBAHAN]

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    function calculateTotal() {
        let total = 0;
        let totalWeight = 0;
        let count = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.price);
                totalWeight += parseFloat(cb.dataset.weight);
                count++;
                document.getElementById('card-' + cb.value).classList.add('selected');
            } else {
                document.getElementById('card-' + cb.value).classList.remove('selected');
            }
        });

        totalEl.innerText = formatRupiah(total);
        countEl.innerText = count;
        btnCheckout.disabled = count === 0;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {

            // ===== VALIDASI 1 TOKO [TAMBAHAN] =====
            if (this.checked) {
                if (!selectedStoreId) {
                    selectedStoreId = this.dataset.storeId;
                } else if (this.dataset.storeId !== selectedStoreId) {
                    alert('❗ Anda hanya bisa checkout produk dari satu toko');
                    this.checked = false;
                    return;
                }
            }

            if ([...checkboxes].every(c => !c.checked)) {
                selectedStoreId = null;
            }
            // =====================================

            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                if (allChecked) selectAllCheckbox.checked = true;
            }

            calculateTotal();
        });
    });

    selectAllCheckbox.addEventListener('change', function() {

        // ===== VALIDASI SELECT ALL [TAMBAHAN] =====
        let firstStore = null;
        for (let cb of checkboxes) {
            if (!firstStore) firstStore = cb.dataset.storeId;
            if (cb.dataset.storeId !== firstStore) {
                alert('❗ Pilih semua hanya boleh dari satu toko');
                this.checked = false;
                return;
            }
        }
        selectedStoreId = firstStore;
        // ========================================

        checkboxes.forEach(cb => cb.checked = this.checked);
        calculateTotal();
    });

    calculateTotal();
});
</script>
</body>
</html>
