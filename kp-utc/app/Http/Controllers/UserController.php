<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        //tampilin semua user & simpen ke variable users
        //User::all() ini syntax buat ambil smua baris data dari model
        $users = User::all();

        //mengembalikan view Blade bernama users/index.blade.php & ngirim variabel $users ke view itu.
        //compact('users') otomatis membuat array ['users' => $users].
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|email|max:100',
            'role_id' => 'required|exists:role,id',
            'status' => 'required|string|in: Available, Not Available',
        ]);

        $password = Str::random(10);

        //cari id role berdasarkan nama role nya (karena di tabel user role disimpen pake id, bkn nama role)
        $role = Role::where('nama', $request->role)->firstOrFail();

        User::create([
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => $role->id,
            'status' => 'Available'
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function resetPassword(User $user)
    {
        $this->authorize('update', $user);
        $password = Str::random(10);
        $user->password = $password;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Password reset successful.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        //rencananya pas super admin add user baru itu ada dropdown buat role
        //buat isi dropdown itu maka perlu semua jenis role yang ada -> pake Role::all()
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request,  User $user)
    {
        $request->validate([
            //pastikan username unik, kecuali untuk username ini sendiri
            'username' => 'required|email|max:100|unique:users,username,' . $user->id,
            'role_id' => 'required|exists:role,id',
            'status' => 'required|string|in: Available, Not Available',
        ]);

        //ini udah gaperlu $user = User::findOrFail($id); lagi karena parameter function ini kan ngirimnya dalam bentuk >>
        //objek User, bukan ngirim id doang jadi nanti code findOrFail itu akan otomatis dijalankan >>
        //laravel tanpa perlu dicode manual, kek dia bakal otomatis nyari user dengan id yang ada di parameter -> nah ini nanti waktu mau update hrs kirim id di parameter??
        User::update([
            'username' => $request->username,
             'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
