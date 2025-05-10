<?php

namespace App\Services;

use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;
use App\Helpers\BidangKeahlianMatcher;

class FuzzySpkService
{
    public function prosesRekomendasi(LombaModel $lomba, array $excludedMahasiswa = [], array $excludedDosen = [])
    {
        $kategoriLomba = $lomba->bidangKeahlian->keahlian ?? 'Lainnya';

        $mahasiswas = MahasiswaModel::with(['sertifikasis', 'bidangKeahlian', 'pengalaman', 'prestasi', 'dosenPembimbing'])
            ->whereNotIn('nim', $excludedMahasiswa)
            ->get();

        $hasilRekomendasi = [];

        foreach ($mahasiswas as $mhs) {
            $dosen = $mhs->dosenPembimbing->first()?->dosen;
            if ($dosen && in_array($dosen->id, $excludedDosen))
                continue;

            // Fuzzy Input: Sertifikasi
            $nilaiSertifikasi = $mhs->sertifikasis->count();

            // Keahlian
            $nilaiKeahlian = $mhs->bidangKeahlian->sum(function ($item) use ($kategoriLomba) {
                return BidangKeahlianMatcher::getSkor($kategoriLomba, $item->keahlian);
            });

            // Pengalaman
            $nilaiPengalaman = $mhs->pengalaman->sum(function ($item) use ($kategoriLomba) {
                return BidangKeahlianMatcher::getSkor($kategoriLomba, $item->kategori);
            });

            // Prestasi (Ambil kategori lomba yang diikuti)
            $nilaiPrestasi = 0;
            foreach ($mhs->prestasi as $prestasi) {
                $kategoriPrestasi = optional($prestasi->lomba)->kategori;
                if ($kategoriPrestasi) {
                    $nilaiPrestasi += BidangKeahlianMatcher::getSkor($kategoriLomba, $kategoriPrestasi);
                }
            }

            // Jumlah Bimbingan
            $jumlahBimbingan = $dosen ? $dosen->pembimbingMahasiswa->count() : 0;

            // Bidang Minat Dosen
            $cocokBidangMinat = 0;
            if ($dosen) {
                $cocokBidangMinat = $dosen->bidangMinat->max(function ($minat) use ($kategoriLomba) {
                    return BidangKeahlianMatcher::getSkor($kategoriLomba, $minat->bidang_minat);
                }) ?? 0;
            }

            // Fuzzifikasi
            $skor = $this->fuzzifikasi($nilaiSertifikasi, $nilaiKeahlian, $nilaiPengalaman, $nilaiPrestasi, $jumlahBimbingan, $cocokBidangMinat);

            \Log::info("NIM: {$mhs->nim}, Skor: {$skor}");
            if ($skor >= 0.6) {
                RekomendasiLombaModel::updateOrCreate([
                    'mahasiswa_nim' => $mhs->nim,
                    'lomba_id' => $lomba->id,
                ], [
                    'dosen_pembimbing_id' => $dosen?->id,
                    'status' => 'Pending',
                ]);

                $hasilRekomendasi[] = [
                    'mahasiswa' => $mhs->nama_lengkap,
                    'skor' => $skor,
                ];
            }
        }

        return $hasilRekomendasi;
    }


    private function fuzzifikasi($sertifikasi, $keahlian, $pengalaman, $prestasi, $bimbingan, $cocok)
    {
        $s = min($sertifikasi / 5, 1);
        $k = min($keahlian / 3, 1);
        $p = min($pengalaman / 3, 1);
        $r = min($prestasi / 5, 1);
        $b = 1 - min($bimbingan / 10, 1); // makin banyak bimbingan, makin rendah nilai
        $bm = $cocok;

        return round(($s + $k + $p + $r + $b + $bm) / 6, 2);
    }

    public function gantiPenggantiMahasiswa(LombaModel $lomba, string $ditolakNim)
    {
        return $this->prosesRekomendasi($lomba, [$ditolakNim]);
    }

    public function gantiPenggantiDosen(LombaModel $lomba, int $ditolakDosenId)
    {
        return $this->prosesRekomendasi($lomba, [], [$ditolakDosenId]);
    }

    public function gantiPenggantiSemua(LombaModel $lomba, string $nim, int $dosenId)
    {
        return $this->prosesRekomendasi($lomba, [$nim], [$dosenId]);
    }
}
