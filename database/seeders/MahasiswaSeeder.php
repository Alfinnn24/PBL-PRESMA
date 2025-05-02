<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mahasiswa')->insert([
            [
                'nim' => '2201001',
                'user_id' => 2,
                'nama_lengkap' => 'Ahmad Maulana',
                'angkatan' => 2022,
                'no_telp' => '08123456789',
                'alamat' => 'Jl. Merdeka No. 10',
                'program_studi_id' => 1,
                'bidang_keahlian' => 'Web Development',
                'pengalaman' => 'Magang di Telkom',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
