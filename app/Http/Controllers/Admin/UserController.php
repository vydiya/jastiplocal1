<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required',
            'email'  => 'required|email|unique:users',
            'password' => 'required|min:6',
            'peran'  => 'required|in:admin,pengguna,jastiper'
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.pengguna.index')
            ->with('success','Pengguna berhasil ditambahkan!');
    }

    public function edit(User $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'name'   => 'required',
            'email'  => "required|email|unique:users,email,{$pengguna->id}",
            'peran'  => 'required|in:admin,pengguna,jastiper'
        ]);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')
            ->with('success','Pengguna berhasil diperbarui!');
    }

    public function destroy(User $pengguna)
    {
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success','Pengguna berhasil dihapus!');
    }
}