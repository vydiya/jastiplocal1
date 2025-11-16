<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // GET /admin/pengguna
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(15);
        return view('admin.pengguna.index', compact('users'));
    }

    // GET /admin/pengguna/create
    public function create()
    {
        return view('admin.pengguna.create');
    }

    // POST /admin/pengguna
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'username'      => 'nullable|string|max:50|unique:users,username',
            'nama_lengkap'  => 'nullable|string|max:150',
            'no_hp'         => 'nullable|string|max:30',
            'alamat'        => 'nullable|string',
            'role'          => ['nullable', Rule::in(['pengguna','admin','jastiper'])],
            'tanggal_daftar'=> 'nullable|date',
        ]);

        // Hash password (model juga punya cast 'hashed' - tapi kita hash eksplisit di controller)
        $data['password'] = Hash::make($data['password']);

        // jika tanggal_daftar tidak diisi, biarkan DB gunakan current timestamp (migration pakai useCurrent)
        if (empty($data['tanggal_daftar'])) {
            unset($data['tanggal_daftar']);
        }

        User::create($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    // GET /admin/pengguna/{pengguna}
    public function show(User $pengguna)
    {
        return view('admin.pengguna.show', ['user' => $pengguna]);
    }

    // GET /admin/pengguna/{pengguna}/edit
    public function edit(User $pengguna)
    {
        return view('admin.pengguna.edit', ['user' => $pengguna]);
    }

    // PUT/PATCH /admin/pengguna/{pengguna}
    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => ['required','email','max:255', Rule::unique('users','email')->ignore($pengguna->id)],
            'username'      => ['nullable','string','max:50', Rule::unique('users','username')->ignore($pengguna->id)],
            'nama_lengkap'  => 'nullable|string|max:150',
            'no_hp'         => 'nullable|string|max:30',
            'alamat'        => 'nullable|string',
            'role'          => ['nullable', Rule::in(['pengguna','admin','jastiper'])],
            'tanggal_daftar'=> 'nullable|date',
            // password optional (ditangani di bawah)
        ]);

        // jika user mengisi password baru
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        } else {
            // Jangan overwrite password jika tidak diisi
            unset($data['password']);
        }

        // jika tanggal_daftar kosong, jangan overwrite
        if (empty($data['tanggal_daftar'])) {
            unset($data['tanggal_daftar']);
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // DELETE /admin/pengguna/{pengguna}
    public function destroy(User $pengguna)
    {
        try {
            $pengguna->delete();
            return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Throwable $e) {
            // kemungkinan constraint FK atau error lain
            return redirect()->route('admin.pengguna.index')->with('error', 'Gagal menghapus pengguna. Pastikan tidak ada data terkait atau hubungi admin.');
        }
    }
}
