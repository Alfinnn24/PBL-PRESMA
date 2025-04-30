<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periode')->insert([
            ['nama' => '2024/2025 Ganjil', 'tahun' => 2024, 'semester' => 'Ganjil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2024/2025 Genap', 'tahun' => 2025, 'semester' => 'Genap', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
