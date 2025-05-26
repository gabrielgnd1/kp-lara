<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    \App\Models\User::create([
        'username' => 'lapangan',
        'name' => 'Lapangan',
        'email' => 'lapangan@utc.com',
        'password' => \Illuminate\Support\Facades\Hash::make('test1234'),
        'status' => 'Available',
        'id_role' => 3,
    ]);
}
}
