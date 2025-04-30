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
                'mahasiswa_nim' => '2201001',
                'lomba_id' => 1,
                'status' => 'Terdaftar',
                'hasil' => '',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
