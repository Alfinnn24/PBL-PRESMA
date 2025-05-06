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
            ['id_minat' => 1, 'dosen_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 2, 'dosen_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 3, 'dosen_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 4, 'dosen_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 5, 'dosen_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 6, 'dosen_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 7, 'dosen_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 8, 'dosen_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 9, 'dosen_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id_minat' => 10, 'dosen_id' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }
}

?>