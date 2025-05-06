<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('program_studi')->insert([
            ['nama_prodi' => 'Teknik Informatika', 'kode_prodi' => 'IF01', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Sistem Informasi Bisnis', 'kode_prodi' => 'SI01', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Teknik Mesin', 'kode_prodi' => 'TK01', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Manajemen Informatika', 'kode_prodi' => 'MI01', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Teknik Elektronika', 'kode_prodi' => 'TE01', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Teknik Listrik', 'kode_prodi' => 'TL01', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Teknik Telekomunikasi', 'kode_prodi' => 'TT01', 'jenjang' => 'D3', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Rekayasa Perangkat Lunak', 'kode_prodi' => 'RPL1', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Data Science', 'kode_prodi' => 'DS01', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Kecerdasan Buatan', 'kode_prodi' => 'AI01', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
