<?php

namespace App\Services;

use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;

class FuzzySpkService
{
    public function prosesRekomendasi(LombaModel $lomba, array $excludedMahasiswa = [], array $excludedDosen = [])
    {
        $mahasiswas = MahasiswaModel::with(['sertifikasis', 'bidangKeahlian', 'pengalaman', 'prestasi', 'dosenPembimbing'])
            ->whereNotIn('nim', $excludedMahasiswa)
            ->get();

        $hasilRekomendasi = [];

        foreach ($mahasiswas as $mhs) {
            $dosen = $mhs->dosenPembimbing->first()?->dosen;
            if ($dosen && in_array($dosen->id, $excludedDosen))
                continue;

            $nilaiSertifikasi = $mhs->sertifikasis->count();
            $nilaiKeahlian = $mhs->bidangKeahlian->count();
            $nilaiPengalaman = $mhs->pengalaman->count();
            $nilaiPrestasi = $mhs->prestasi->count();

            $jumlahBimbingan = $dosen ? $dosen->pembimbingMahasiswa->count() : 0;
            $cocokBidangMinat = $dosen && $dosen->bidangMinat->pluck('bidang_minat')->contains($lomba->bidang_keahlian) ? 1 : 0;

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
        $b = min($bimbingan / 10, 1);
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
