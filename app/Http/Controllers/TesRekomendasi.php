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

        foreach ($lombas as $lomba) {
            $hasilRekomendasi = $this->topsisService->prosesRekomendasi($lomba);

            foreach ($hasilRekomendasi as $rekom) {
                $nim = $rekom['nim'];
                $skor = $rekom['skor'];

                $mahasiswa = MahasiswaModel::with('dosenPembimbing.dosen')->where('nim', $nim)->first();
                $dosenPembimbingId = $mahasiswa->dosenPembimbing->first()?->dosen_id ?? null;

                RekomendasiLombaModel::updateOrCreate(
                    [
                        'mahasiswa_nim' => $nim,
                        'lomba_id' => $lomba->id,
                    ],
                    [
                        'dosen_pembimbing_id' => $dosenPembimbingId,
                        'status' => 'Pending',
                        'skor' => $skor, // <- PASTIKAN INI ADA
                    ]
                );
            }
        }

        return response()->json([
            'message' => 'Semua lomba telah diproses dengan metode TOPSIS dan hasil disimpan.',
        ]);
    }

    public function lihatHasilTopsis($idLomba)
    {
        $lomba = LombaModel::with('bidangKeahlian')->findOrFail($idLomba);

        $hasilRekomendasi = $this->topsisService->prosesRekomendasi($lomba);

        return response()->json($hasilRekomendasi);
    }
}
