<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenPembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen_pembimbing')->insert([
            ['dosen_id' => 1, 'mahasiswa_nim' => '2201001', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
