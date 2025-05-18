<?php
namespace Database\Seeders;
// database/seeders/PengalamanSeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PengalamanModel;
use Carbon\Carbon;

class PengalamanSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengalaman')->insert([
            // Web Development - 2341720172
            [
                'pengalaman' => 'Magang di PT Telkom Indonesia',
                'kategori' => 'Web Development',
                'mahasiswa_nim' => '2341720172',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Freelance Web Developer di Upwork',
                'kategori' => 'Web Development',
                'mahasiswa_nim' => '2341720172',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Data Science - 2341720182
            [
                'pengalaman' => 'Magang di Google',
                'kategori' => 'Data Science',
                'mahasiswa_nim' => '2341720182',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Asisten Peneliti Analisis Data',
                'kategori' => 'Data Science',
                'mahasiswa_nim' => '2341720182',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // UI/UX Design - 2341720231
            [
                'pengalaman' => 'Intern di Apple',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'UI Designer di Startup Lokal',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Freelance UX Audit',
                'kategori' => 'UI/UX Design',
                'mahasiswa_nim' => '2341720231',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Mobile App Development - 2341720256
            [
                'pengalaman' => 'Magang di Microsoft',
                'kategori' => 'Mobile App Development',
                'mahasiswa_nim' => '2341720256',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Android Developer di Aplikasi Startup',
                'kategori' => 'Mobile App Development',
                'mahasiswa_nim' => '2341720256',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Cybersecurity - 2341720187
            [
                'pengalaman' => 'Magang di CyberArk',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720187',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Sertifikasi CEH + Praktek',
                'kategori' => 'Cybersecurity',
                'mahasiswa_nim' => '2341720187',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Cloud Computing - 2341720038
            [
                'pengalaman' => 'Intern di AWS',
                'kategori' => 'Cloud Computing',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Deploy Project di Azure & GCP',
                'kategori' => 'Cloud Computing',
                'mahasiswa_nim' => '2341720038',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Artificial Intelligence - 2341720238
            [
                'pengalaman' => 'Intern di IBM',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720238',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'AI Model Developer di Kampus',
                'kategori' => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720238',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Machine Learning - 2341720089
            [
                'pengalaman' => 'Magang di Nvidia',
                'kategori' => 'Machine Learning',
                'mahasiswa_nim' => '2341720089',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Asisten Peneliti Model Klasifikasi',
                'kategori' => 'Machine Learning',
                'mahasiswa_nim' => '2341720089',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // DevOps - 2341720042
            [
                'pengalaman' => 'Magang di GitHub',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720042',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'CI/CD Engineer di Proyek Open Source',
                'kategori' => 'DevOps',
                'mahasiswa_nim' => '2341720042',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Game Development - 2341720103
            [
                'pengalaman' => 'Intern di Unity Technologies',
                'kategori' => 'Game Development',
                'mahasiswa_nim' => '2341720103',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengalaman' => 'Game Designer untuk Game Indie',
                'kategori' => 'Game Development',
                'mahasiswa_nim' => '2341720103',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}

?>