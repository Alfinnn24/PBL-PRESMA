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
            [
                'pengalaman'    => 'Magang di PT Telkom Indonesia',
                'kategori'      => 'Web Development',
                'mahasiswa_nim' => '2341720172',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Magang di Google',
                'kategori'      => 'Data Science',
                'mahasiswa_nim' => '2341720182',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Intern di Apple',
                'kategori'      => 'UI/UX Design',
                'mahasiswa_nim' => '2341720231',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Magang di Microsoft',
                'kategori'      => 'Mobile App Development',
                'mahasiswa_nim' => '2341720256',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Magang di CyberArk',
                'kategori'      => 'Cybersecurity',
                'mahasiswa_nim' => '2341720187',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Intern di AWS',
                'kategori'      => 'Cloud Computing',
                'mahasiswa_nim' => '2341720038',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Intern di IBM',
                'kategori'      => 'Artificial Intelligence',
                'mahasiswa_nim' => '2341720238',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Magang di Nvidia',
                'kategori'      => 'Machine Learning',
                'mahasiswa_nim' => '2341720089',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Magang di GitHub',
                'kategori'      => 'DevOps',
                'mahasiswa_nim' => '2341720042',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pengalaman'    => 'Intern di Unity Technologies',
                'kategori'      => 'Game Development',
                'mahasiswa_nim' => '2341720103',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
        
    }
}

?>