<?php
namespace Database\Seeders;
// database/seeders/DetailBidangMinatSeeder.php
use Illuminate\Database\Seeder;
use App\Models\DetailBidangMinatModel;

class DetailBidangMinatSeeder extends Seeder
{
    public function run()
    {
        DetailBidangMinatModel::insert([
            ['id_minat' => 1, 'dosen_id' => 1, 'created_at' => now()],
        ]);
    }
}

?>