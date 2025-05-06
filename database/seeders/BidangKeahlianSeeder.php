<?php
namespace Database\Seeders;

use App\Models\BidangKeahlianModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BidangKeahlianSeeder extends Seeder
{
    public function run()
    {
        BidangKeahlianModel::insert([
            ['keahlian' => 'Web Development', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Data Science', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'UI/UX Design', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Mobile App Development', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Cybersecurity', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Cloud Computing', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Artificial Intelligence', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Machine Learning', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'DevOps', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Game Development', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Kepenulisan', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Olahraga', 'created_at' => now(), 'updated_at' => now()],
            ['keahlian' => 'Lainnya', 'created_at' => now(), 'updated_at' => now()],
        ]);


    }
}
?>