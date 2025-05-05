<?php
namespace Database\Seeders;
// database/seeders/DetailBidangKeahlianSeeder.php
use Illuminate\Database\Seeder;
use App\Models\DetailBidangKeahlianModel;

class DetailBidangKeahlianSeeder extends Seeder
{
    public function run()
    {
        DetailBidangKeahlianModel::insert([
            ['id_keahlian' => 1, 'mahasiswa_nim' => '2201001', 'created_at' => now()],
            ['id_keahlian' => 2, 'mahasiswa_nim' => '2201001', 'created_at' => now()],
        ]);
    }
}

?>