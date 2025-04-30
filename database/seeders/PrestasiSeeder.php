<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prestasi')->insert([
            [
                'nama_prestasi' => 'Juara 1 Hackathon',
                'lomba_id' => 1,
                'file_bukti' => 'hackathon.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Prestasi tingkat nasional',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
