<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            [
                'user_id' => 1,
                'nama_lengkap' => 'Ilyas Yahya',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 2,
                'nama_lengkap' => 'Nina Kartika',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 3,
                'nama_lengkap' => 'Ahmad Fauzan',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'nama_lengkap' => 'Ratna Dewi',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 5,
                'nama_lengkap' => 'Rizky Firmansyah',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 6,
                'nama_lengkap' => 'Lina Marlina',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 7,
                'nama_lengkap' => 'Budi Santoso',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 8,
                'nama_lengkap' => 'Yuliana Pratiwi',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 9,
                'nama_lengkap' => 'Hendra Kurniawan',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 10,
                'nama_lengkap' => 'Sari Oktaviani',
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);        
    }
}
