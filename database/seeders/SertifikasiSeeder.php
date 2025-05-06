<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sertifikasi')->insert([
            [
                'judul'        => 'Sertifikat Web Development',
                'path'         => '/path/to/certificate/web_dev.pdf',
                'kategori'     => 'Web Development',
                'mahasiswa_nim'=> '2201001',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ]);
    }
}
