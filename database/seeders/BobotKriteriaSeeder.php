<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BobotKriteriaModel; // Pastikan path ini sesuai dengan struktur folder Anda

class BobotKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['kriteria' => 'sertifikasi', 'bobot' => 0.25],
            ['kriteria' => 'keahlian', 'bobot' => 0.30],
            ['kriteria' => 'pengalaman', 'bobot' => 0.20],
            ['kriteria' => 'prestasi', 'bobot' => 0.25],
        ];

        foreach ($data as $item) {
            BobotKriteriaModel::updateOrCreate(
                ['kriteria' => $item['kriteria']],
                ['bobot' => $item['bobot']]
            );
        }
    }
}
