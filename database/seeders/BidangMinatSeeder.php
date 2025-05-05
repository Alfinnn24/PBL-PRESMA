<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\BidangMinatModel;

class BidangMinatSeeder extends Seeder
{
    public function run()
    {
        BidangMinatModel::insert([
            ['bidang_minat' => 'Kecerdasan Buatan', 'created_at' => now()],
            ['bidang_minat' => 'Jaringan Komputer', 'created_at' => now()],
        ]);
    }
}

?>