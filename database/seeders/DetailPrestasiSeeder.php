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
            ['mahasiswa_nim' => '2201001', 'prestasi_id' => 1, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
