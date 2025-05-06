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
                'mahasiswa_nim' => '2341720172',
                'lomba_id' => 1,
                'dosen_pembimbing_id' => 1,
                'status' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720182',
                'lomba_id' => 2,
                'dosen_pembimbing_id' => 2,
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720231',
                'lomba_id' => 3,
                'dosen_pembimbing_id' => 3,
                'status' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720256',
                'lomba_id' => 4,
                'dosen_pembimbing_id' => 4,
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720187',
                'lomba_id' => 5,
                'dosen_pembimbing_id' => 5,
                'status' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720038',
                'lomba_id' => 6,
                'dosen_pembimbing_id' => 6,
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720238',
                'lomba_id' => 7,
                'dosen_pembimbing_id' => 7,
                'status' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720089',
                'lomba_id' => 8,
                'dosen_pembimbing_id' => 8,
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720042',
                'lomba_id' => 9,
                'dosen_pembimbing_id' => 9,
                'status' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mahasiswa_nim' => '2341720103',
                'lomba_id' => 10,
                'dosen_pembimbing_id' => 10,
                'status' => 'Ditolak',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
        
    }
}
