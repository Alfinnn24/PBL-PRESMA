<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DetailPrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_prestasi')->insert([
            ['mahasiswa_nim' => '2341720172', 'prestasi_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720182', 'prestasi_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720231', 'prestasi_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720256', 'prestasi_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720187', 'prestasi_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720038', 'prestasi_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720238', 'prestasi_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720089', 'prestasi_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720042', 'prestasi_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_nim' => '2341720103', 'prestasi_id' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }
}
