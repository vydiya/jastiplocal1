<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;

class KeranjangController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat keranjang.');
        }

        $userId = Auth::id();
        $cart = session('cart', []);
        $items = [];
        $productIds = [];

        $userCart = collect($cart)->filter(function ($item, $key) use ($userId) {
            return Str::startsWith($key, $userId . '-');
        });

        foreach ($userCart as $key => $data) {
            $productId = explode('-', $key)[1];
            $productIds[] = $productId;
            $items[$productId] = $data['qty'];
        }

        $produks = Barang::whereIn('id', $productIds)
            ->with('jastiper')
            ->get()
            ->keyBy('id');

        $cartDetails = [];
        $subtotal = 0;
        $totalBerat = 0;

        foreach ($items as $productId => $qty) {
            $produk = $produks->get($productId);

            if ($produk) {
                $totalHargaItem = $produk->harga * $qty;
                $subtotal += $totalHargaItem;
                $totalBerat += ($produk->berat_gr ?? 1) * $qty;

                $cartDetails[] = (object) [
                    'produk' => $produk,
                    'qty' => $qty,
                    'total_harga' => $totalHargaItem,
                    'cart_key' => $userId . '-' . $productId,
                ];
            }
        }

        $cartCount = count($userCart);
        $total_final = $subtotal;

        $userName = Auth::user()->name ?? 'Pengguna';

        return view('user.keranjang.index', compact(
            'cartDetails',
            'subtotal',
            'totalBerat',
            'total_final',
            'cartCount',
            'userName'
        ));
    }

   public function tambah(Request $request, $productId)
   {
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login dulu!');
    }

    $request->validate(['qty' => 'required|integer|min:1']);

    $barang = Barang::findOrFail($productId);
    if ($barang->stok < $request->qty) {
        return back()->with('error', 'Stok tidak mencukupi!');
    }

    $key = Auth::id() . '-' . $productId;
    $cart = session('cart', []);

    // FIX: qty tidak menimpa
    $cart[$key] = [
        'qty' => ($cart[$key]['qty'] ?? 0) + $request->qty
    ];

    session(['cart' => $cart]);

    // FIX: Buy Now langsung ke checkout
    if ($request->input('action') === 'buy_now') {
        // Jika buy now, kita set ini sebagai selected item di session checkout
        session(['checkout_selected_ids' => [$productId]]);
        return redirect()->route('checkout.index');
    }

    return redirect()->route('keranjang.index')
        ->with('success', 'Berhasil ditambahkan ke keranjang!');
   }


    public function update(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate(['qty' => 'required|integer|min:1']);

        $userId = Auth::id();
        $key = $userId . '-' . $productId;
        $newQty = $request->input('qty');

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $barang = Barang::find($productId);
            if ($barang && $barang->stok >= $newQty) {
                $cart[$key]['qty'] = $newQty;
                session(['cart' => $cart]);
                return redirect()->route('keranjang.index');
            } else {
                return redirect()->route('keranjang.index')->with('error', 'Kuantitas melebihi stok yang tersedia.');
            }
        }

        return redirect()->route('keranjang.index')->with('error', 'Item tidak ditemukan di keranjang.');
    }

    public function hapus($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $key = $userId . '-' . $productId;
        $cart = session('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session(['cart' => $cart]);
            return redirect()->route('keranjang.index')->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()->route('keranjang.index')->with('error', 'Item tidak ditemukan di keranjang.');
    }
}