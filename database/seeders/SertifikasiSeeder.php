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
                'judul' => 'Laravel Fundamental Certificate',
                'path' => 'sertifikat/laravel.pdf',
                'mahasiswa_nim' => '2201001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'judul' => 'Web Development Bootcamp',
                'path' => 'sertifikat/web_dev.pdf',
                'mahasiswa_nim' => '2201001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'AI and Machine Learning',
                'path' => 'sertifikat/ai_ml.pdf',
                'mahasiswa_nim' => '2201001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
