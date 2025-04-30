<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RekomendasiLombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rekomendasi_lomba')->insert([
            [
                'mahasiswa_nim' => '2201001',
                'lomba_id' => 1,
                'dosen_pembimbing_id' => 1,
                'status' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
