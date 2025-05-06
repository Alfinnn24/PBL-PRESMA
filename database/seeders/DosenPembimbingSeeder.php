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
            ['dosen_id' => 1, 'mahasiswa_nim' => '2341720172', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 2, 'mahasiswa_nim' => '2341720182', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 3, 'mahasiswa_nim' => '2341720231', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 4, 'mahasiswa_nim' => '2341720256', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 5, 'mahasiswa_nim' => '2341720187', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 6, 'mahasiswa_nim' => '2341720038', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 7, 'mahasiswa_nim' => '2341720238', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 8, 'mahasiswa_nim' => '2341720089', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 9, 'mahasiswa_nim' => '2341720042', 'created_at' => now(), 'updated_at' => now()],
            ['dosen_id' => 10, 'mahasiswa_nim' => '2341720103', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }
}
