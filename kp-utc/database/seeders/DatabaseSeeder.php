<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::create([
        'username' => 'adminbaru',
        'name' => 'Admin Baru',
        'email' => 'adminbaru@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('passwordku123'),
        'status' => 'Available',
        'id_role' => 1,
    ]);
}
}
