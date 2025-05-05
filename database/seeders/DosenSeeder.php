<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen')->insert([
            [
                'user_id' => 3,
                'program_studi_id' => 1,
                'nidn' => '1234567890',
                'nama_lengkap' => 'Dr. Siti Aminah',
                'no_telp' => '082233445566',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
