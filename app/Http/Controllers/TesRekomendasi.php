<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\RekomendasiLombaModel;
use App\Services\TopsisSpkService;

class TesRekomendasi extends Controller
{
    protected $topsisService;

    public function __construct(TopsisSpkService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function prosesSemuaLombaDenganTopsis()
    {
        $lombas = LombaModel::with('bidangKeahlian')->get();

        $hasilAkhir = [];

        foreach ($lombas as $lomba) {
            $hasilRekomendasi = $this->topsisService->prosesRekomendasi($lomba);

            foreach ($hasilRekomendasi as $rekom) {
                $hasilAkhir[] = [
                    'lomba_id' => $lomba->id,
                    'lomba_nama' => $lomba->nama ?? '-',
                    'nim' => $rekom['nim'],
                    'nama' => $rekom['nama'],
                    'skor' => $rekom['skor'],
                    'matriks' => $rekom['matriks'] ?? [],
                    'dosen_pembimbing_id' => null,
                ];
            }
        }

        // Simpan hasil rekomendasi ke dalam tabel rekomendasi_lomba
        foreach ($hasilAkhir as $data) {
            RekomendasiLombaModel::updateOrCreate(
                [
                    'lomba_id' => $data['lomba_id'],
                    'mahasiswa_nim' => $data['nim'],
                ],
                [
                    'nama' => $data['nama'],
                    'skor' => $data['skor'],
                    'dosen_pembimbing_id' => $data['dosen_pembimbing_id'],
                    'status' => 'Pending',
                ]
            );
        }

        return response()->json($hasilAkhir);
    }

    public function lihatHasilTopsis($idLomba)
    {
        $lomba = LombaModel::with('bidangKeahlian')->findOrFail($idLomba);

        $hasilRekomendasi = $this->topsisService->prosesRekomendasi($lomba);

        return response()->json($hasilRekomendasi);
    }
}
