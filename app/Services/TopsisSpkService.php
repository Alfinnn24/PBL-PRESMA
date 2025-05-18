<?php

namespace App\Services;

use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;
use App\Helpers\BidangKeahlianMatcher;

class TopsisSpkService
{
    /**
     * Proses rekomendasi menggunakan metode TOPSIS
     *
     * @param LombaModel $lomba
     * @param array $excludedMahasiswa
     * @return array
     */
    public function prosesRekomendasi(LombaModel $lomba, array $excludedMahasiswa = [])
    {
        $kategoriLomba = $lomba->bidangKeahlian->keahlian ?? 'Lainnya';

        // Ambil data mahasiswa dengan relasi yang diperlukan
        $mahasiswas = MahasiswaModel::with(['sertifikasis', 'bidangKeahlian', 'pengalaman', 'prestasi'])
            ->whereNotIn('nim', $excludedMahasiswa)
            ->get();

        $kriteria = ['sertifikasi', 'keahlian', 'pengalaman', 'prestasi'];

        // 1. Bangun matriks keputusan
        $matriks = [];
        foreach ($mahasiswas as $mhs) {
            // Hitung nilai sertifikasi dengan matching bidang keahlian
            $nilaiSertifikasi = 0;
            foreach ($mhs->sertifikasis as $sertifikasi) {
                $bidangSertifikasi = $sertifikasi->bidang_keahlian ?? 'Lainnya';
                $nilaiSertifikasi += BidangKeahlianMatcher::getSkor($kategoriLomba, $bidangSertifikasi);
            }

            $nilaiKeahlian = $mhs->bidangKeahlian->sum(function ($item) use ($kategoriLomba) {
                return BidangKeahlianMatcher::getSkor($kategoriLomba, $item->keahlian);
            });

            $nilaiPengalaman = $mhs->pengalaman->sum(function ($item) use ($kategoriLomba) {
                return BidangKeahlianMatcher::getSkor($kategoriLomba, $item->kategori);
            });

            $nilaiPrestasi = 0;
            foreach ($mhs->prestasi as $prestasi) {
                $kategoriPrestasi = optional($prestasi->lomba)->kategori;
                if ($kategoriPrestasi) {
                    $nilaiPrestasi += BidangKeahlianMatcher::getSkor($kategoriLomba, $kategoriPrestasi);
                }
            }

            $matriks[] = [
                'nim' => $mhs->nim,
                'nama' => $mhs->nama_lengkap,
                'nilai' => [
                    $nilaiSertifikasi,
                    $nilaiKeahlian,
                    $nilaiPengalaman,
                    $nilaiPrestasi,
                ],
            ];
        }

        if (count($matriks) === 0) {
            return []; // Tidak ada mahasiswa
        }

        // 2. Bobot kriteria (pastikan jumlah bobot = 1)
        $bobot = [0.25, 0.30, 0.20, 0.25];

        // 3. Normalisasi matriks keputusan
        $jumlahKuadrat = array_fill(0, count($kriteria), 0);

        foreach ($matriks as $row) {
            foreach ($row['nilai'] as $j => $nilai) {
                $jumlahKuadrat[$j] += pow($nilai, 2);
            }
        }
        $akarJumlahKuadrat = array_map(fn($x) => sqrt($x), $jumlahKuadrat);

        $normalisasi = [];
        foreach ($matriks as $row) {
            $normalisasi[] = [
                'nim' => $row['nim'],
                'nama' => $row['nama'],
                'nilai' => array_map(fn($nilai, $j) => $akarJumlahKuadrat[$j] != 0 ? $nilai / $akarJumlahKuadrat[$j] : 0, $row['nilai'], array_keys($row['nilai'])),
            ];
        }

        // 4. Matriks terbobot
        $matriksTerbobot = [];
        foreach ($normalisasi as $row) {
            $nilaiTerbobot = [];
            foreach ($row['nilai'] as $j => $nilai) {
                $nilaiTerbobot[] = $nilai * $bobot[$j];
            }
            $matriksTerbobot[] = [
                'nim' => $row['nim'],
                'nama' => $row['nama'],
                'nilai' => $nilaiTerbobot,
            ];
        }

        // 5. Tentukan solusi ideal positif dan negatif (kriteria benefit semua)
        $solusiIdealPositif = [];
        $solusiIdealNegatif = [];
        for ($j = 0; $j < count($kriteria); $j++) {
            $kolom = array_column(array_map(fn($row) => $row['nilai'][$j], $matriksTerbobot), null);
            $solusiIdealPositif[$j] = max($kolom);
            $solusiIdealNegatif[$j] = min($kolom);
        }

        // 6. Hitung jarak ke solusi ideal positif dan negatif
        $hasil = [];
        foreach ($matriksTerbobot as $row) {
            $jarakPositif = 0;
            $jarakNegatif = 0;
            foreach ($row['nilai'] as $j => $nilai) {
                $jarakPositif += pow($nilai - $solusiIdealPositif[$j], 2);
                $jarakNegatif += pow($nilai - $solusiIdealNegatif[$j], 2);
            }
            $jarakPositif = sqrt($jarakPositif);
            $jarakNegatif = sqrt($jarakNegatif);

            // 7. Hitung nilai preferensi
            $nilaiPreferensi = $jarakNegatif / ($jarakNegatif + $jarakPositif);

            $hasil[] = [
                'nim' => $row['nim'],
                'nama' => $row['nama'],
                'skor' => round($nilaiPreferensi, 4),
            ];
        }

        // 8. Urutkan berdasarkan skor preferensi descending
        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // // Simpan atau update rekomendasi di database
        // foreach ($hasil as $item) {
        //     RekomendasiLombaModel::updateOrCreate(
        //         [
        //             'mahasiswa_nim' => $item['nim'],
        //             'lomba_id' => $lomba->id,
        //         ],
        //         [
        //             'status' => 'Pending',
        //         ]
        //     );
        // }

        return $hasil;
    }
}
