<?php

namespace App\Services;

use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;
use App\Helpers\BidangKeahlianMatcher;

class TopsisSpkService
{
    public function prosesRekomendasi(LombaModel $lomba, array $excludedMahasiswa = [])
    {
        $kategoriLomba = $lomba->bidangKeahlian->keahlian ?? 'Lainnya';

        $mahasiswas = MahasiswaModel::with(['sertifikasis', 'bidangKeahlian.bidangKeahlian', 'pengalaman', 'prestasi'])
            ->whereNotIn('nim', $excludedMahasiswa)
            ->get();

        $kriteria = ['sertifikasi', 'keahlian', 'pengalaman', 'prestasi'];

        // Matriks keputusan awal
        $matriks = [];
        foreach ($mahasiswas as $mhs) {
            $nilaiSertifikasi = 0;
            foreach ($mhs->sertifikasis as $sertifikasi) {
                $bidang = $sertifikasi->kategori ?? 'Lainnya';
                $nilaiSertifikasi += BidangKeahlianMatcher::getSkor($kategoriLomba, $bidang);
                // \Log::info("Lomba {$kategoriLomba} Kategori {$bidang}");
                // \Log::info("Nilai sertifikasi {$mhs->nama_lengkap}: {$nilaiSertifikasi}");
            }

            $nilaiKeahlian = 0;
            foreach ($mhs->bidangKeahlian as $item) {
                $keahlian = $item->bidangKeahlian->keahlian ?? null;
                if ($keahlian) {
                    $nilaiKeahlian += BidangKeahlianMatcher::getSkor($kategoriLomba, $keahlian);
                    // \Log::info("Lomba {$kategoriLomba} Kategori {$keahlian}");
                    // \Log::info("Nilai keahlian {$mhs->nama_lengkap}: {$nilaiKeahlian}");
                }
            }

            $nilaiPengalaman = 0;
            foreach ($mhs->pengalaman as $item) {
                $kategoriItem = $item->kategori ?? 'Lainnya';
                $nilaiPengalaman += BidangKeahlianMatcher::getSkor($kategoriLomba, $kategoriItem);
                // \Log::info("Lomba {$kategoriLomba} Kategori {$kategoriItem}");
                // \Log::info("Nilai pengalaman {$mhs->nama_lengkap}: {$nilaiPengalaman}");
            }

            $nilaiPrestasi = 0;
            foreach ($mhs->prestasi as $detailPrestasi) {
                $prestasi = $detailPrestasi->prestasi;

                // Cek apakah prestasi ada dan sudah disetujui
                if ($prestasi && $prestasi->status === 'Disetujui') {
                    $kategoriPrestasi = optional($prestasi->lomba->bidangKeahlian)->keahlian;
                    if ($kategoriPrestasi) {
                        $nilaiPrestasi += BidangKeahlianMatcher::getSkor($kategoriLomba, $kategoriPrestasi);
                        // \Log::info("Lomba {$kategoriLomba} Kategori {$kategoriPrestasi}");
                        // \Log::info("Nilai prestasi {$mhs->nama_lengkap}: {$nilaiPrestasi}");
                    }
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
            // \Log::info('Matriks keputusan', [
            //     'nim' => $mhs->nim,
            //     'nama' => $mhs->nama_lengkap,
            //     'nilai' => [
            //         'sertifikasi' => $nilaiSertifikasi,
            //         'keahlian' => $nilaiKeahlian,
            //         'pengalaman' => $nilaiPengalaman,
            //         'prestasi' => $nilaiPrestasi,
            //     ],
            // ]);
        }

        // \Log::info('Matriks keputusan awal', $matriks);

        if (count($matriks) === 0)
            return [];

        // Bobot
        $bobot = [0.25, 0.30, 0.20, 0.25];

        // Normalisasi
        $jumlahKuadrat = array_fill(0, count($kriteria), 0);
        foreach ($matriks as $row) {
            foreach ($row['nilai'] as $j => $nilai) {
                $jumlahKuadrat[$j] += pow($nilai, 2);
            }
        }

        $akarJumlahKuadrat = array_map(fn($x) => sqrt($x ?: 1), $jumlahKuadrat); // Hindari pembagian 0


        // \Log::info('Akar jumlah kuadrat', $akarJumlahKuadrat);

        $normalisasi = [];
        foreach ($matriks as $row) {
            $normalisasi[] = [
                'nim' => $row['nim'],
                'nama' => $row['nama'],
                'nilai' => array_map(
                    fn($nilai, $j) => $nilai / ($akarJumlahKuadrat[$j] ?: 1),
                    $row['nilai'],
                    array_keys($row['nilai'])
                ),
            ];
            // \Log::info('Normalisasi', [
            //     'nim' => $row['nim'],
            //     'nama' => $row['nama'],
            //     'nilai' => [
            //         'sertifikasi' => $normalisasi[array_key_last($normalisasi)]['nilai'][0],
            //         'keahlian' => $normalisasi[array_key_last($normalisasi)]['nilai'][1],
            //         'pengalaman' => $normalisasi[array_key_last($normalisasi)]['nilai'][2],
            //         'prestasi' => $normalisasi[array_key_last($normalisasi)]['nilai'][3],
            //     ],
            // ]);
        }

        // Matriks terbobot
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
            // \Log::info('Matriks terbobot', [
            //     'nim' => $row['nim'],
            //     'nama' => $row['nama'],
            //     'nilai' => [
            //         'sertifikasi' => $nilaiTerbobot[0],
            //         'keahlian' => $nilaiTerbobot[1],
            //         'pengalaman' => $nilaiTerbobot[2],
            //         'prestasi' => $nilaiTerbobot[3],
            //     ],
            // ]);
        }

        // Solusi ideal
        $solusiIdealPositif = [];
        $solusiIdealNegatif = [];
        for ($j = 0; $j < count($kriteria); $j++) {
            $kolom = array_column($matriksTerbobot, 'nilai');
            $nilaiKolom = array_column($kolom, $j);
            $solusiIdealPositif[$j] = max($nilaiKolom);
            $solusiIdealNegatif[$j] = min($nilaiKolom);
        }

        // \Log::info('Solusi ideal positif', $solusiIdealPositif);
        // \Log::info('Solusi ideal negatif', $solusiIdealNegatif);
        // Hitung skor preferensi
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
            $denominator = $jarakNegatif + $jarakPositif;

            $nilaiPreferensi = $denominator > 0 ? $jarakNegatif / $denominator : 0;

            $hasil[] = [
                'nim' => $row['nim'],
                'nama' => $row['nama'],
                'skor' => round($nilaiPreferensi, 4),
            ];
            // \Log::info('Hasil preferensi', [
            //     'nim' => $row['nim'],
            //     'nama' => $row['nama'],
            //     'skor' => $nilaiPreferensi,
            // ]);
        }

        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return $hasil;
    }

    public function prosesSemuaLombaDenganTopsis()
    {
        // Hapus rekomendasi untuk lomba yang statusnya 'Ditolak' atau tanggal selesai < hari ini
        $lombaDitolakAtauKadaluarsa = LombaModel::where(function ($q) {
            $q->where('is_verified', 'Ditolak')
                ->orWhereDate('tanggal_selesai', '<', now()->toDateString());
        })
            ->pluck('id')
            ->toArray();

        if (!empty($lombaDitolakAtauKadaluarsa)) {
            RekomendasiLombaModel::whereIn('lomba_id', $lombaDitolakAtauKadaluarsa)->delete();
        }

        $lombas = LombaModel::with('bidangKeahlian')
            ->where('is_verified', 'Disetujui')
            ->whereDate('tanggal_selesai', '>=', now()->toDateString())
            ->get();
        $hasilAkhir = [];

        foreach ($lombas as $lomba) {
            $hasilRekomendasi = $this->prosesRekomendasi($lomba);

            foreach ($hasilRekomendasi as $rekom) {
                $hasilAkhir[] = [
                    'lomba_id' => $lomba->id,
                    'lomba_nama' => $lomba->nama ?? '-',
                    'nim' => $rekom['nim'],
                    'nama' => $rekom['nama'],
                    'skor' => $rekom['skor'],
                    'dosen_pembimbing_id' => null,
                ];
            }
        }

        foreach ($hasilAkhir as $data) {
            $rekom = RekomendasiLombaModel::where('lomba_id', $data['lomba_id'])
                ->where('mahasiswa_nim', $data['nim'])
                ->first();

            if ($rekom) {
                $rekom->update(['skor' => $data['skor']]);
            } else {
                // Cari dosen_pembimbing_id dan status_dosen dari rekomendasi lomba yang sama jika ada
                $existingRekom = RekomendasiLombaModel::where('lomba_id', $data['lomba_id'])
                    ->whereNotNull('dosen_pembimbing_id')
                    ->orderByDesc('id')
                    ->first();

                $dosenPembimbingId = $existingRekom ? $existingRekom->dosen_pembimbing_id : null;
                $statusDosen = $existingRekom ? $existingRekom->status_dosen : null;

                RekomendasiLombaModel::create([
                    'lomba_id' => $data['lomba_id'],
                    'mahasiswa_nim' => $data['nim'],
                    'skor' => $data['skor'],
                    'status' => 'Pending',
                    'dosen_pembimbing_id' => $dosenPembimbingId,
                    'status_dosen' => $statusDosen,
                ]);
            }
        }

        return $hasilAkhir;
    }
}
