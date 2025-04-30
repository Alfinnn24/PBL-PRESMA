<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lomba')->insert([
            [
                'nama' => 'Hackathon Indonesia',
                'kategori' => 'Teknologi',
                'penyelenggara' => 'Kemenkominfo',
                'tingkat' => 'Nasional',
                'bidang_keahlian' => 'Pengembangan Aplikasi',
                'persyaratan' => 'Mahasiswa aktif',
                'link_registrasi' => 'https://HackathonIndonesia.com',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2024-09-10',
                'periode_id' => 1,
                'created_by' => 1,
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
