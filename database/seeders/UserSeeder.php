<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            ['username' => 'admin1', 'email' => 'admin@gmail.com', 'password' => Hash::make('password'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs1', 'email' => 'mhs1@gmail.com', 'password' => Hash::make('password'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn1', 'email' => 'dsn1@gmail.com', 'password' => Hash::make('password'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
