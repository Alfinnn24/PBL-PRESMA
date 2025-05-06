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
            // Admin
            ['username' => 'admin1', 'email' => 'admin1@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin2', 'email' => 'admin2@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin3', 'email' => 'admin3@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin4', 'email' => 'admin4@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin5', 'email' => 'admin5@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin6', 'email' => 'admin6@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin7', 'email' => 'admin7@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin8', 'email' => 'admin8@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin9', 'email' => 'admin9@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'admin10', 'email' => 'admin10@gmail.com', 'password' => Hash::make('123456'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        
            // Mahasiswa
            ['username' => 'mhs1', 'email' => 'mhs1@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs2', 'email' => 'mhs2@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs3', 'email' => 'mhs3@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs4', 'email' => 'mhs4@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs5', 'email' => 'mhs5@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs6', 'email' => 'mhs6@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs7', 'email' => 'mhs7@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs8', 'email' => 'mhs8@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs9', 'email' => 'mhs9@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'mhs10', 'email' => 'mhs10@gmail.com', 'password' => Hash::make('123456'), 'role' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
        
            // Dosen
            ['username' => 'dsn1', 'email' => 'dsn1@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn2', 'email' => 'dsn2@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn3', 'email' => 'dsn3@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn4', 'email' => 'dsn4@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn5', 'email' => 'dsn5@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn6', 'email' => 'dsn6@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn7', 'email' => 'dsn7@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn8', 'email' => 'dsn8@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn9', 'email' => 'dsn9@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'dsn10', 'email' => 'dsn10@gmail.com', 'password' => Hash::make('123456'), 'role' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
        ]);        
    }
}
