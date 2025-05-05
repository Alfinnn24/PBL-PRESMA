<?php
namespace Database\Seeders;
// database/seeders/DetailPengalamanSeeder.php
use Illuminate\Database\Seeder;
use App\Models\DetailPengalamanModel;

class DetailPengalamanSeeder extends Seeder
{
    public function run()
    {
        DetailPengalamanModel::insert([
            ['id_pengalaman' => 1, 'mahasiswa_nim' => '2201001', 'created_at' => now()],
        ]);
    }
}

?>