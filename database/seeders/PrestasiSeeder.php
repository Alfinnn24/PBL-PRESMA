<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prestasi')->insert([
            [
                'nama_prestasi' => 'Juara 1 Hackathon',
                'lomba_id' => 1,
                'file_bukti' => 'hackathon.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Prestasi tingkat nasional',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Juara Harapan UI/UX',
                'lomba_id' => 2,
                'file_bukti' => 'uiux_challenge.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Presentasi sangat baik',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Finalis Data Science Competition',
                'lomba_id' => 3,
                'file_bukti' => 'datascience_finalist.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Tembus 10 besar nasional',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Best Innovation AI Fest',
                'lomba_id' => 4,
                'file_bukti' => 'ai_fest_innovation.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Pengakuan internasional',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Juara 2 Cybersecurity Games',
                'lomba_id' => 5,
                'file_bukti' => 'cybersecurity_winner.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Tim terbaik kedua',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Top 5 Cloud Innovation',
                'lomba_id' => 6,
                'file_bukti' => 'cloudchallenge_top5.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Presentasi dan prototipe unggul',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Juara Favorit DevOps',
                'lomba_id' => 7,
                'file_bukti' => 'devops_favorit.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Proyek disukai juri',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Runner Up Game Jam',
                'lomba_id' => 8,
                'file_bukti' => 'gamejam_runnerup.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Gameplay kreatif dan unik',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Best App Mobile Dev',
                'lomba_id' => 9,
                'file_bukti' => 'mobiledev_bestapp.pdf',
                'status' => 'Disetujui',
                'catatan' => 'User interface menarik',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_prestasi' => 'Top 3 ML Championship',
                'lomba_id' => 10,
                'file_bukti' => 'mlchampionship_top3.pdf',
                'status' => 'Disetujui',
                'catatan' => 'Model akurat dan efisien',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
