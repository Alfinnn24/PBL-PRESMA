<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PendaftaranLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pendaftaran_lomba')->insert([
            [
                'mahasiswa_nim' => '2341720172',
                'lomba_id' => 1,
                'status' => 'Terdaftar',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720182',
                'lomba_id' => 1,
                'status' => 'Selesai',
                'hasil' => 'Juara 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720231',
                'lomba_id' => 2,
                'status' => 'Terdaftar',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720256',
                'lomba_id' => 2,
                'status' => 'Dibatalkan',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720187',
                'lomba_id' => 3,
                'status' => 'Selesai',
                'hasil' => 'Juara 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720038',
                'lomba_id' => 3,
                'status' => 'Terdaftar',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720238',
                'lomba_id' => 4,
                'status' => 'Selesai',
                'hasil' => 'Juara Harapan 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720089',
                'lomba_id' => 4,
                'status' => 'Dibatalkan',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720042',
                'lomba_id' => 5,
                'status' => 'Terdaftar',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_nim' => '2341720103',
                'lomba_id' => 5,
                'status' => 'Selesai',
                'hasil' => 'Juara 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);        
    }
}
