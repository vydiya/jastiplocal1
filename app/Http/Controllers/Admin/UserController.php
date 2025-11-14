<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // pakai model User bawaan Laravel (sesuaikan jika beda)

class UserController extends Controller
{
    // GET /admin/pengguna
    public function index()
    {
        $users = User::paginate(15);
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
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->route('admin.pengguna.index')->with('success','User created.');
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
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email,'.$pengguna->id,
            // password optional
        ]);

        if($request->filled('password')){
            $request->validate(['password' => 'string|min:6|confirmed']);
            $data['password'] = bcrypt($request->password);
        }

        $pengguna->update($data);
        return redirect()->route('admin.pengguna.index')->with('success','User updated.');
    }

    // DELETE /admin/pengguna/{pengguna}
    public function destroy(User $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('admin.pengguna.index')->with('success','User deleted.');
    }
}
