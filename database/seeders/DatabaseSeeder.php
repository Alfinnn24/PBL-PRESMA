<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProgramStudiSeeder::class,
            UserSeeder::class,
            PeriodeSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            SertifikasiSeeder::class,
            DosenPembimbingSeeder::class,
            LombaSeeder::class,
            PrestasiSeeder::class,
            DetailPrestasiSeeder::class,
            PendaftaranLombaSeeder::class,
            RekomendasiLombaSeeder::class,
            BidangKeahlianSeeder::class,
            PengalamanSeeder::class,
            BidangMinatSeeder::class,
            DetailBidangKeahlianSeeder::class,
            DetailBidangMinatSeeder::class,
        ]);
    }
}
