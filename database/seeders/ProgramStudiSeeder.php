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
            ['nama_prodi' => 'Teknik Informatika', 'kode_prodi' => 'IF01', 'jenjang' => 'D4', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
