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
            ['nama' => '2020/2021', 'tahun' => 2020, 'semester' => 'Ganjil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2020/2021', 'tahun' => 2021, 'semester' => 'Genap', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2021/2022', 'tahun' => 2021, 'semester' => 'Ganjil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2021/2022', 'tahun' => 2022, 'semester' => 'Genap', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2022/2023', 'tahun' => 2022, 'semester' => 'Ganjil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2022/2023', 'tahun' => 2023, 'semester' => 'Genap', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2023/2024', 'tahun' => 2023, 'semester' => 'Ganjil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2023/2024', 'tahun' => 2024, 'semester' => 'Genap', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2024/2025', 'tahun' => 2024, 'semester' => 'Ganjil', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => '2024/2025', 'tahun' => 2025, 'semester' => 'Genap', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
