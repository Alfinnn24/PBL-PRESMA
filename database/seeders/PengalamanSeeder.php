<?php
namespace Database\Seeders;
// database/seeders/PengalamanSeeder.php
use Illuminate\Database\Seeder;
use App\Models\PengalamanModel;

class PengalamanSeeder extends Seeder
{
    public function run()
    {
        PengalamanModel::insert([
            ['pengalaman' => 'Magang di perusahaan A', 'created_at' => now()],
            ['pengalaman' => 'Proyek freelance aplikasi mobile', 'created_at' => now()],
        ]);
    }
}

?>