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
                'nim' => '2341720172',
                'user_id' => 11,
                'nama_lengkap' => 'ACHMAD MAULANA HAMZAH',
                'angkatan' => 2023,
                'no_telp' => '08123456701',
                'alamat' => 'Jl. Anggrek No. 1',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720182',
                'user_id' => 12,
                'nama_lengkap' => 'ALVANZA SAPUTRA YUDHA',
                'angkatan' => 2023,
                'no_telp' => '08123456702',
                'alamat' => 'Jl. Tulip No. 2',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720231',
                'user_id' => 13,
                'nama_lengkap' => 'ANYA CALLISSTA CHRISWANTARI',
                'angkatan' => 2023,
                'no_telp' => '08123456703',
                'alamat' => 'Jl. Mawar No. 3',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720256',
                'user_id' => 14,
                'nama_lengkap' => 'BERYL FUNKY MUBAROK',
                'angkatan' => 2023,
                'no_telp' => '08123456704',
                'alamat' => 'Jl. Teratai No. 4',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720187',
                'user_id' => 15,
                'nama_lengkap' => 'CANDRA AHMAD DANI',
                'angkatan' => 2023,
                'no_telp' => '08123456705',
                'alamat' => 'Jl. Dahlia No. 5',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720038',
                'user_id' => 16,
                'nama_lengkap' => 'CINDY LAILI LARASATI',
                'angkatan' => 2023,
                'no_telp' => '08123456706',
                'alamat' => 'Jl. Melati No. 6',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720238',
                'user_id' => 17,
                'nama_lengkap' => 'DIKA ARIE ARRIKY',
                'angkatan' => 2023,
                'no_telp' => '08123456707',
                'alamat' => 'Jl. Kamboja No. 7',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720089',
                'user_id' => 18,
                'nama_lengkap' => 'FAHMI YAHYA',
                'angkatan' => 2023,
                'no_telp' => '08123456708',
                'alamat' => 'Jl. Lily No. 8',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720042',
                'user_id' => 19,
                'nama_lengkap' => 'GILANG PURNOMO',
                'angkatan' => 2023,
                'no_telp' => '08123456709',
                'alamat' => 'Jl. Tulip No. 9',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '2341720103',
                'user_id' => 20,
                'nama_lengkap' => 'GWIDO PUTRA WIJAYA',
                'angkatan' => 2023,
                'no_telp' => '08123456710',
                'alamat' => 'Jl. Kemuning No. 10',
                'program_studi_id' => 1,
                'foto_profile' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
