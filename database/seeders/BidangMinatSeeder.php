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
            ['bidang_minat' => 'Web Development', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Data Science', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'UI/UX Design', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Mobile App Development', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Cybersecurity', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Cloud Computing', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Artificial Intelligence', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Machine Learning', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'DevOps', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Game Development', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Kepenulisan', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Olahraga', 'created_at' => now(), 'updated_at' => now()],
            ['bidang_minat' => 'Lainnya', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }
}

?>