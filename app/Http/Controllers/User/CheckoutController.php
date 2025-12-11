<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Rekening;
use App\Models\AlurDana;
use App\Notifications\PembayaranUserBaru;

class CheckoutController extends Controller
{
    private $adminUserId = 1;

    // --- FUNGSI BARU: TERIMA DATA DARI KERANJANG ---
    public function prepareCheckout(Request $request)
    {
        // 1. Validasi: Harus ada barang yang dipilih (array)
        $request->validate([
            'selected_products' => 'required|array',
            'selected_products.*' => 'exists:barangs,id',
        ]);

        // 2. Simpan ID barang yang dipilih ke SESSION SEMENTARA
        session(['checkout_selected_ids' => $request->selected_products]);
        
        // 3. Reset step ke 1
        session(['checkout_step' => 1]); 

        // 4. Redirect ke halaman checkout utama
        return redirect()->route('checkout.index');
    }

    private function getAdminBankAccounts()
    {
        return Rekening::where('user_id', $this->adminUserId)
                       ->where('status_aktif', 'aktif')
                       ->get();
    }

    private function getCartData(int $userId)
    {
        $cart = session('cart', []);
        
        // --- MODIFIKASI: AMBIL FILTER DARI SESSION ---
        $selectedIds = session('checkout_selected_ids', []);

        // Jika tidak ada ID yang dipilih, kembalikan null (agar diredirect balik ke keranjang)
        if (empty($selectedIds)) {
            return null;
        }

        // Filter cart: Hanya ambil item milik User INI dan ID-nya ada di daftar SELECTED
        $userCart = collect($cart)->filter(function($item, $key) use ($userId, $selectedIds) {
            $parts = explode('-', $key);
            $cartUserId = $parts[0];
            $cartProductId = $parts[1] ?? null;

            // Logic: User Cocok DAN Product ID ada di array Selected
            return $cartUserId == $userId && in_array($cartProductId, $selectedIds);
        });

        if ($userCart->isEmpty()) return null;

        $itemsQty = [];
        $productIds = [];
        foreach ($userCart as $key => $data) {
            $productId = explode('-', $key)[1];
            $productIds[] = $productId;
            $itemsQty[$productId] = $data['qty'];
        }

        $produks = Barang::whereIn('id', $productIds)->with('jastiper')->get()->keyBy('id');

        $cartDetails = [];
        $subtotal = 0;
        $totalBerat = 0;

        foreach ($itemsQty as $productId => $qty) {
            $produk = $produks->get($productId);
            if ($produk) {
                $totalHargaItem = $produk->harga * $qty;
                $subtotal += $totalHargaItem;
                $totalBerat += ($produk->berat_gr ?? 1) * $qty;

                $cartDetails[] = (object)[
                    'produk' => $produk,
                    'qty' => $qty,
                    'total_harga' => $totalHargaItem,
                ];
            }
        }

        return (object)[
            'cartDetails' => $cartDetails,
            'subtotal' => $subtotal,
            'totalBerat' => $totalBerat,
            'total_final' => $subtotal,
        ];
    }

    private function clearUserCartFromSession($userId)
    {
        $cart = session('cart', []);
        
        // --- MODIFIKASI: HAPUS HANYA YANG DIPILIH (DIBAYAR) ---
        $selectedIds = session('checkout_selected_ids', []);

        foreach ($selectedIds as $productId) {
            $key = $userId . '-' . $productId;
            if (isset($cart[$key])) {
                unset($cart[$key]);
            }
        }

        // Simpan sisa keranjang
        session(['cart' => $cart]);
        
        // Bersihkan session temporary selected IDs
        session()->forget('checkout_selected_ids');
    }

    private function createOrderAndStoreIdInSession($cartDetails, $totalFinal, $alamatData, $userId)
    {
        DB::beginTransaction();
        try {
            $jastiperId = $cartDetails[0]->produk->jastiper_id ?? null;
            $alamatFull = $alamatData['alamat_lengkap'] . ', ' . $alamatData['kota'] . ', ' . $alamatData['provinsi'] . ' (' . $alamatData['kode_pos'] . ')';

            $pesanan = Pesanan::create([
                'user_id' => $userId,
                'jastiper_id' => $jastiperId,
                'total_harga' => $totalFinal,
                'alamat_pengiriman' => $alamatFull,
                'catatan' => $alamatData['catatan'] ?? null,
                'status_pesanan' => 'MENUNGGU_PEMBAYARAN',
                'status_dana_jastiper' => 'TERTAHAN',
                'no_hp' => Auth::user()->no_hp ?? null,
            ]);

            foreach ($cartDetails as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'barang_id' => $item->produk->id,
                    'jumlah' => $item->qty,
                    'subtotal' => $item->total_harga,
                ]);
            }

            DB::commit();
            session(['current_pesanan_id' => $pesanan->id]);
            return $pesanan->id;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan checkout.');
        }

        $userId = Auth::id();
        
        // --- CEK: Jika tidak ada barang dipilih & tidak ada pesanan aktif -> Balik ke keranjang ---
        if (!session()->has('checkout_selected_ids') && !session('current_pesanan_id')) {
             return redirect()->route('keranjang.index')->with('error', 'Silakan pilih barang yang ingin dibeli terlebih dahulu.');
        }

        $cartData = $this->getCartData($userId);
        $pesananId = session('current_pesanan_id');
        
        $step = session('checkout_step', 1);

        if (!$pesananId && $step > 2 && $cartData) {
            $step = 1;
            session(['checkout_step' => 1]);
        } 
        elseif (!$pesananId && !$cartData) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang belanja Anda kosong (atau item yang dipilih tidak valid).');
        }

        $cartDetails = $cartData?->cartDetails ?? [];
        $subtotal     = $cartData?->subtotal ?? 0;
        $totalBerat   = $cartData?->totalBerat ?? 0;
        $total_final  = $cartData?->total_final ?? 0;

        $rekeningAdmin = $this->getAdminBankAccounts();

        if ($pesananId) {
            $pesanan = Pesanan::find($pesananId);
            if ($pesanan) {
                $total_final = $pesanan->total_harga;
            } else {
                session()->forget(['current_pesanan_id', 'checkout_step']);
                return redirect()->route('keranjang.index')->with('error', 'Pesanan tidak ditemukan.');
            }
        }

        $cartCount = count(session('cart', []));

        return view('user.checkout.index', compact(
            'cartDetails', 'subtotal', 'totalBerat', 'total_final',
            'step', 'pesananId', 'rekeningAdmin', 'cartCount'
        ));
    }

    public function processStep(Request $request)
    {
        if (!Auth::check()) { return redirect()->route('login'); }
        
        $userId = Auth::id();
        $currentStep = (int) $request->input('current_step');
        $nextStep = $currentStep + 1;

        // PROSES DARI STEP 1 KE 2
        if ($currentStep == 1) { 
            session(['checkout_step' => $nextStep]);
            return redirect()->route('checkout.index');
        } 
        
        // PROSES DARI STEP 2 KE 3 (Create Order)
        else if ($currentStep == 2) { 
            
            $request->validate([
                'alamat_lengkap' => 'required|string|min:10',
                'provinsi' => 'required|string',
                'kota' => 'required|string',
                'kode_pos' => 'required|numeric',
                'catatan' => 'nullable|string|max:255',
            ]);
            
            $alamatData = $request->only(['alamat_lengkap', 'provinsi', 'kota', 'kode_pos', 'catatan']);
            
            $cartData = $this->getCartData($userId);
            if (!$cartData) { return redirect()->route('keranjang.index')->with('error', 'Keranjang kosong saat checkout.'); }

            try {
                $this->createOrderAndStoreIdInSession($cartData->cartDetails, $cartData->total_final, $alamatData, $userId);
                
                session(['checkout_step' => $nextStep]);
                session(['checkout_address' => $alamatData]);
                return redirect()->route('checkout.index');

            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
            }
        }
        
        // PROSES DARI STEP 3 KE 4 (Upload Bukti)
        else if ($currentStep == 3) { 
            
            $request->validate([
                'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'pesanan_id' => 'required|exists:pesanans,id', 
            ]);
            
            $pesananId = $request->input('pesanan_id');
            $pesanan = Pesanan::find($pesananId);
            
            if (!$pesanan || $pesanan->status_pesanan !== 'MENUNGGU_PEMBAYARAN') {
                return back()->with('error', 'Status pesanan tidak sesuai.');
            }

            $path = $request->file('bukti_transfer')->store('bukti_transfer/user', 'public');
            
            try {
                DB::beginTransaction();

                AlurDana::create([
                    'pesanan_id' => $pesananId,
                    'jenis_transaksi' => 'PEMBAYARAN_USER',
                    'jumlah_dana' => $pesanan->total_harga,
                    'bukti_tf_path' => $path,
                    'status_konfirmasi' => 'MENUNGGU_CEK',
                    'tanggal_transfer' => now(), 
                ]);

                $pesanan->update([
                    'status_pesanan' => 'MENUNGGU_KONFIRMASI_ADMIN',
                ]);
                
                // HAPUS ITEM DARI SESSION (Hanya yang dipilih)
                $this->clearUserCartFromSession($userId);
                
                session(['checkout_step' => $nextStep]); 
                
                DB::commit();
                
                $admin = User::find($this->adminUserId);
                if ($admin) {
                    $admin->notify(new PembayaranUserBaru($pesanan));
                }
                
                return redirect()->route('checkout.index')->with('success', 'Pembayaran berhasil diunggah.');

            } catch (\Exception $e) {
                DB::rollBack();
                Storage::disk('public')->delete($path);
                return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('checkout.index');
    }
    
    public function finalizeCheckout()
    {
        session()->forget('current_pesanan_id');
        session()->forget('checkout_step');
        session()->forget('checkout_address');
        session()->forget('checkout_selected_ids'); // Pastikan ini juga dibersihkan
        
        return redirect()->route('pesanan.riwayat');
    }

    public function previousStep()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $currentStep = session('checkout_step', 1);
        $previousStep = max(1, $currentStep - 1);
        
        session(['checkout_step' => $previousStep]);

        return redirect()->route('checkout.index');
    }
}