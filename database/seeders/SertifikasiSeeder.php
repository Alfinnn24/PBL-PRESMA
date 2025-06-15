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
                'path' => 'sertifikat/sertifikasi.pdf',
                'kategori' => 'Web Development',
                'mahasiswa_nim' => '2341720172',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Data Science',
                'path' => 'sertifikat/sertifikasi2.pdf',
                'kategori' => 'Data Science',
                'mahasiswa_nim' => '2341720172',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720182
            [
                'judul' => 'Sertifikat Data Science',
                'path' => 'sertifikat/sertifikasi3.pdf',
                'kategori' => 'Data Science',
                'mahasiswa_nim' => '2341720182',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Cloud Computing',
                'path' => 'sertifikat/sertifikasi4.pdf',
                'kategori' => 'Cloud Computing',
                'mahasiswa_nim' => '2341720182',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720231
            [
                'judul' => 'Sertifikat UI/UX Design',
                'path' => 'sertifikat/sertifikasi5.pdf',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Mobile App Development',
                'path' => 'sertifikat/sertifikasi6.pdf',
                'kategori' => 'Mobile App Development',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Web Development',
                'path' => 'sertifikat/sertifikasi7.pdf',
                'kategori' => 'Web Development',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720256
            [
                'judul' => 'Sertifikat Mobile App Development',
                'path' => 'sertifikat/sertifikasi8.pdf',
                'kategori' => 'Mobile App Development',
                'mahasiswa_nim' => '2341720256',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Cybersecurity',
                'path' => 'sertifikat/sertifikasi9.pdf',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720256',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720187
            [
                'judul' => 'Sertifikat Cybersecurity',
                'path' => 'sertifikat/sertifikasi10.pdf',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720187',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat DevOps',
                'path' => 'sertifikat/sertifikasi11.pdf',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720187',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720038
            [
                'judul' => 'Sertifikat Cloud Computing',
                'path' => 'sertifikat/sertifikasi12.pdf',
                'kategori' => 'Cloud Computing',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Artificial Intelligence',
                'path' => 'sertifikat/sertifikasi2.pdf',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Machine Learning',
                'path' => 'sertifikat/sertifikasi3.pdf',
                'kategori' => 'Machine Learning',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720238
            [
                'judul' => 'Sertifikat Artificial Intelligence',
                'path' => 'sertifikat/sertifikasi14.pdf',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720238',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat UI/UX Design',
                'path' => 'sertifikat/sertifikasi15.pdf',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720238',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720089
            [
                'judul' => 'Sertifikat Machine Learning',
                'path' => 'sertifikat/sertifikasi16.pdf',
                'kategori' => 'Machine Learning',
                'mahasiswa_nim' => '2341720089',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat DevOps',
                'path' => 'sertifikat/sertifikasi17.pdf',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720089',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720042
            [
                'judul' => 'Sertifikat DevOps',
                'path' => 'sertifikat/sertifikasi18.pdf',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720042',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Cybersecurity',
                'path' => 'sertifikat/sertifikasi19.pdf',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720042',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 2341720103
            [
                'judul' => 'Sertifikat Game Development',
                'path' => 'sertifikat/sertifikasi20.pdf',
                'kategori' => 'Game Development',
                'mahasiswa_nim' => '2341720103',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Sertifikat Artificial Intelligence',
                'path' => 'sertifikat/sertifikasi21.pdf',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720103',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
