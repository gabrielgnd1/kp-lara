<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

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

    //buat nampilin create.blade.php
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

        //saat admin pertama kali create user, bakal auto generate password 10 karakter, campuran huruf & angka
        $password = Str::random(10);

        //cari id role berdasarkan nama role nya (karena di tabel user role disimpen pake id, bkn nama role)
        //$request->$role ini itu hasil input dari form
        //lalu cari hasil dari tabel Role di kolom nama yang isinya sama dengan $role
        //firstOrFail ini syntax laravel buat ambil baris pertama dari hasil query, klo gaada, bakal return error 404 not found
        //output dari ini itu nantinya akan berisi smua atribut dari role itu sendiri
        //ex: $role->id = 1; $role->nama = super admin
        $role = Role::where('nama', $request->role)->firstOrFail();

        //simpan data user baru ke tabel User
        //jalanin query create
        $user->create([
            'username' => $request->username,
            'password' => $password,
            'role_id' => $role->id,
            'status' => 'Available'
        ]);

        //jika sukses, redirect ke index.blade.php
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    //buat superadmin jika ada user yang lupa password
    public function resetPassword(User $user)
    {
        $password = Str::random(10);
        $user->password = $password;
        //simpan perubahan data di database
        $user->save();

        //jika sukses, redirect ke index.blade.php
        return redirect()->route('users.index')->with('success', 'Password reset successful.');
    }

    //buat nampilin form edit.blade.php & kirim value roles
    public function edit(User $user)
    {
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

        //ini udah gaperlu $user = User::findOrFail($id) lagi karena parameter function ini kan ngirimnya >>
        //dalam bentuk objek User, bukan ngirim id doang jadi nanti code findOrFail itu akan otomatis >>
        //dijalankan  laravel tanpa perlu dicode manual, kek dia bakal otomatis nyari user dengan id >>
        //yang ada di parameter -> nah ini nanti waktu mau update hrs kirim id di parameter??

        //ini jalanin query update
        $user->update([
            'username' => $request->username,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        //jika sukses, redirect ke index.blade.php
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    //buat menonaktifkan suatu user
    public function deactivate(User $user)
    {
        $user->status = 'Not Available';
        $user->save();

        //jika sukses, redirect ke index.blade.php
        return redirect()->route('users.index')->with('success', 'User status set to Not Available.');
    }
}
