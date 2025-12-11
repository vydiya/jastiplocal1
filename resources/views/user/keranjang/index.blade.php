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
        
        /* Style saat item dipilih */
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

        /* Summary Styles */
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

        @media (max-width: 768px) {
            .cart-container {
                flex-direction: column;
            }
            .cart-items, .cart-summary {
                min-width: 100%;
            }
            .cart-summary {
                position: static;
            }
        }
    </style>
</head>

<body>
    {{-- Header --}}
    @include('user.layout.header', [
        'isLoggedIn' => $userIsLoggedIn,
        'cartCount' => $cartCount,
        'searchValue' => '',
        'userName' => $userName,
    ])

    <div class="cart-container">
        <div class="cart-items">
            <h2 class="section-title" style="margin-bottom: 1.5rem; padding-top: 1rem;">Keranjang Belanja Anda ({{ $cartCount }} Item)</h2>

            @if(session('success'))
                <div style="background: #e6ffed; color: #1f7a22; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background: #ffe6e6; color: #cc0000; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="margin-bottom: 1rem; padding-left: 0.5rem;">
                <label style="display: flex; align-items: center; cursor: pointer; font-weight: 600;">
                    <input type="checkbox" id="select-all" class="item-checkbox"> Pilih Semua
                </label>
            </div>

            @forelse ($cartDetails as $item)
                <div class="item-card" id="card-{{ $item->produk->id }}">
                    
                    {{-- CHECKBOX SELECTION --}}
                    {{-- Atribut form="checkout-form" menghubungkan checkbox ini ke form di sidebar --}}
                    <input type="checkbox" 
                           name="selected_products[]" 
                           value="{{ $item->produk->id }}" 
                           class="item-checkbox product-checkbox"
                           form="checkout-form"
                           data-price="{{ $item->total_harga }}"
                           data-weight="{{ ($item->produk->berat_gr ?? 1) * $item->qty }}">

                    <div class="item-image">
                        @if ($item->produk->foto_barang)
                            <img src="{{ asset('storage/' . $item->produk->foto_barang) }}" alt="{{ $item->produk->nama_barang }}">
                        @else
                            <i class="fas fa-box" style="font-size: 2rem; color: rgba(0,0,0,0.1); padding: 1rem;"></i>
                        @endif
                    </div>

                    <div class="item-details">
                        <a href="{{ route('produk.detail', $item->produk->id) }}" class="item-name">{{ $item->produk->nama_barang }}</a>
                        <div class="item-store">{{ $item->produk->jastiper->nama_toko ?? 'Toko Tidak Dikenal' }}</div>
                        <div class="item-store">Lokasi: {{ $item->produk->jastiper->jangkauan ?? '-' }}</div>
                    </div>

                    <div class="item-actions">
                        {{-- Form Update Kuantitas --}}
                        <form action="{{ route('keranjang.update', $item->produk->id) }}" method="POST" class="quantity-control" style="display: flex; gap: 0.5rem;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="qty_action" value="decrease">
                            <button type="button" onclick="
                                const input = this.closest('form').querySelector('input[name=qty]');
                                input.value = Math.max(1, parseInt(input.value) - 1); 
                                this.closest('form').submit();
                            ">-</button>
                            <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->produk->stok ?? 1 }}" readonly>
                            <input type="hidden" name="qty_action" value="increase">
                            <button type="button" onclick="
                                const input = this.closest('form').querySelector('input[name=qty]');
                                input.value = Math.min({{ $item->produk->stok ?? 1 }}, parseInt(input.value) + 1); 
                                this.closest('form').submit();
                            ">+</button>
                        </form>

                        {{-- Form Hapus Item --}}
                        <form action="{{ route('keranjang.hapus', $item->produk->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn" title="Hapus Item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

                    <div class="item-price">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding: 4rem;">
                    <i class="fas fa-shopping-basket"></i>
                    <p style="margin-top: 1rem;">Keranjang Anda masih kosong. Yuk, cari produk favorit Anda!</p>
                    <a href="{{ route('home') }}" class="btn-checkout" style="width: auto; display: inline-block; background: #FFDD00; color: #333;">Mulai Belanja</a>
                </div>
            @endforelse
        </div>

        @if ($cartCount > 0)
            <div class="cart-summary">
                <h3 style="margin-bottom: 1.5rem; color: #006FFF;">Ringkasan Belanja</h3>

                {{-- FORM CHECKOUT UTAMA --}}
                <form id="checkout-form" action="{{ route('checkout.prepare') }}" method="POST">
                    @csrf
                    
                    <div class="summary-row">
                        <span>Total Harga Barang</span>
                        <span id="summary-subtotal">Rp 0</span>
                    </div>

                    <div class="summary-row">
                        <span>Total Berat</span>
                        <span id="summary-weight">0 kg</span>
                    </div>

                    <div class="summary-total summary-row">
                        <span>Total</span>
                        <span id="summary-total">Rp 0</span>
                    </div>

                    <button type="submit" class="btn-checkout" id="btn-checkout" disabled>
                        Checkout (<span id="count-selected">0</span>)
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- SCRIPT HITUNG HARGA DINAMIS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const selectAllCheckbox = document.getElementById('select-all');
            const subtotalEl = document.getElementById('summary-subtotal');
            const totalEl = document.getElementById('summary-total');
            const weightEl = document.getElementById('summary-weight');
            const countEl = document.getElementById('count-selected');
            const btnCheckout = document.getElementById('btn-checkout');

            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            };

            function calculateTotal() {
                let total = 0;
                let totalWeight = 0;
                let count = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.getAttribute('data-price'));
                        totalWeight += parseFloat(cb.getAttribute('data-weight'));
                        count++;
                        document.getElementById('card-' + cb.value).classList.add('selected');
                    } else {
                        document.getElementById('card-' + cb.value).classList.remove('selected');
                    }
                });

                subtotalEl.innerText = formatRupiah(total);
                totalEl.innerText = formatRupiah(total);
                weightEl.innerText = (totalWeight / 1000).toFixed(2) + ' kg';
                countEl.innerText = count;

                if (count > 0) {
                    btnCheckout.removeAttribute('disabled');
                } else {
                    btnCheckout.setAttribute('disabled', 'true');
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        const allChecked = Array.from(checkboxes).every(c => c.checked);
                        if(allChecked) selectAllCheckbox.checked = true;
                    }
                    calculateTotal();
                });
            });

            if(selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    calculateTotal();
                });
            }

            calculateTotal();
        });
    </script>
</body>
</html>