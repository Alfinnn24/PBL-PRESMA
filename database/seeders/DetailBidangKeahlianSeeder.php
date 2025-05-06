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
            ['id_keahlian' => 1, 'mahasiswa_nim' => '2341720172', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 2, 'mahasiswa_nim' => '2341720182', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 3, 'mahasiswa_nim' => '2341720231', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 4, 'mahasiswa_nim' => '2341720256', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 5, 'mahasiswa_nim' => '2341720187', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 6, 'mahasiswa_nim' => '2341720038', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 7, 'mahasiswa_nim' => '2341720238', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 8, 'mahasiswa_nim' => '2341720089', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 9, 'mahasiswa_nim' => '2341720042', 'created_at' => now(), 'updated_at' => now()],
            ['id_keahlian' => 10, 'mahasiswa_nim' => '2341720103', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }
}

?>