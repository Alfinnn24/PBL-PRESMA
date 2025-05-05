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
            ['keahlian' => 'Web Development', 'created_at' => now()],
            ['keahlian' => 'Data Science', 'created_at' => now()],
            ['keahlian' => 'UI/UX Design', 'created_at' => now()],
        ]);
    }
}
?>