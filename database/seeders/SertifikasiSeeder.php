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
            // 2341720172
            [
                'judul' => 'Sertifikat Web Development',
                'path' => '/path/to/certificate/web_dev.pdf',
                'kategori' => 'Web Development',
                'mahasiswa_nim' => '2341720172',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Data Science',
                'path' => '/path/to/certificate/data_science_2.pdf',
                'kategori' => 'Data Science',
                'mahasiswa_nim' => '2341720172',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720182
            [
                'judul' => 'Sertifikat Data Science',
                'path' => '/path/to/certificate/data_science.pdf',
                'kategori' => 'Data Science',
                'mahasiswa_nim' => '2341720182',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Cloud Computing',
                'path' => '/path/to/certificate/cloud_computing_2.pdf',
                'kategori' => 'Cloud Computing',
                'mahasiswa_nim' => '2341720182',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720231
            [
                'judul' => 'Sertifikat UI/UX Design',
                'path' => '/path/to/certificate/ui_ux_design.pdf',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Mobile App Development',
                'path' => '/path/to/certificate/mobile_app_2.pdf',
                'kategori' => 'Mobile App Development',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Web Development',
                'path' => '/path/to/certificate/web_dev_2.pdf',
                'kategori' => 'Web Development',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720256
            [
                'judul' => 'Sertifikat Mobile App Development',
                'path' => '/path/to/certificate/mobile_app.pdf',
                'kategori' => 'Mobile App Development',
                'mahasiswa_nim' => '2341720256',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Cybersecurity',
                'path' => '/path/to/certificate/cybersecurity_2.pdf',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720256',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720187
            [
                'judul' => 'Sertifikat Cybersecurity',
                'path' => '/path/to/certificate/cybersecurity.pdf',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720187',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat DevOps',
                'path' => '/path/to/certificate/devops_2.pdf',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720187',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720038
            [
                'judul' => 'Sertifikat Cloud Computing',
                'path' => '/path/to/certificate/cloud_computing.pdf',
                'kategori' => 'Cloud Computing',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Artificial Intelligence',
                'path' => '/path/to/certificate/ai_2.pdf',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Machine Learning',
                'path' => '/path/to/certificate/machine_learning_2.pdf',
                'kategori' => 'Machine Learning',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720238
            [
                'judul' => 'Sertifikat Artificial Intelligence',
                'path' => '/path/to/certificate/ai.pdf',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720238',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat UI/UX Design',
                'path' => '/path/to/certificate/ui_ux_design_2.pdf',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720238',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720089
            [
                'judul' => 'Sertifikat Machine Learning',
                'path' => '/path/to/certificate/machine_learning.pdf',
                'kategori' => 'Machine Learning',
                'mahasiswa_nim' => '2341720089',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat DevOps',
                'path' => '/path/to/certificate/devops_3.pdf',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720089',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720042
            [
                'judul' => 'Sertifikat DevOps',
                'path' => '/path/to/certificate/devops.pdf',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720042',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Cybersecurity',
                'path' => '/path/to/certificate/cybersecurity_3.pdf',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720042',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720103
            [
                'judul' => 'Sertifikat Game Development',
                'path' => '/path/to/certificate/game_dev.pdf',
                'kategori' => 'Game Development',
                'mahasiswa_nim' => '2341720103',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Artificial Intelligence',
                'path' => '/path/to/certificate/ai_3.pdf',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720103',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
