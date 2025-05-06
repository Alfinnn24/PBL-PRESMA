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
                'user_id' => 21,
                'program_studi_id' => 1,
                'nidn' => '1234567891',
                'nama_lengkap' => 'Dimas Wahyu Wibowo, ST., MT',
                'no_telp' => '082233445561',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 22,
                'program_studi_id' => 1,
                'nidn' => '1234567892',
                'nama_lengkap' => 'Ahmadi Yuli Ananta, ST., MM.',
                'no_telp' => '082233445562',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 23,
                'program_studi_id' => 1,
                'nidn' => '1234567893',
                'nama_lengkap' => 'Arief Prasetyo, S.Kom',
                'no_telp' => '082233445563',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 24,
                'program_studi_id' => 1,
                'nidn' => '1234567894',
                'nama_lengkap' => 'Cahya Rahmad, ST., M.Kom., Dr. Eng',
                'no_telp' => '082233445564',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 25,
                'program_studi_id' => 1,
                'nidn' => '1234567895',
                'nama_lengkap' => 'Dwi Puspitasari, S.Kom., M.Kom',
                'no_telp' => '082233445565',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 26,
                'program_studi_id' => 1,
                'nidn' => '1234567896',
                'nama_lengkap' => 'Mungki Astiningrum, ST., M.Kom',
                'no_telp' => '082233445566',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 27,
                'program_studi_id' => 1,
                'nidn' => '1234567897',
                'nama_lengkap' => 'Pramana Yoga Saputra, S.KOM., MM',
                'no_telp' => '082233445567',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 28,
                'program_studi_id' => 1,
                'nidn' => '1234567898',
                'nama_lengkap' => 'Putra Prima Arhandi, ST., M.Kom',
                'no_telp' => '082233445568',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 29,
                'program_studi_id' => 1,
                'nidn' => '1234567899',
                'nama_lengkap' => 'Yuri Ariyanto, S.Kom., M.Kom',
                'no_telp' => '082233445569',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 30,
                'program_studi_id' => 1,
                'nidn' => '1234567900',
                'nama_lengkap' => 'Ulla Delfana Rosiani, ST., MT',
                'no_telp' => '082233445570',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);        
    }
}
