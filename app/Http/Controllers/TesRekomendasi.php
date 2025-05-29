<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\RekomendasiLombaModel;
use App\Services\TopsisSpkService;

class TesRekomendasi extends Controller
{
    public $topsisService;

    public function __construct(TopsisSpkService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function prosesSemuaLombaDenganTopsis()
    {
        // Ambil hanya lomba yang disetujui
        $lombas = LombaModel::with('bidangKeahlian')
            ->where('is_verified', 'Disetujui')
            ->get();

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
                    // 'matriks' => $rekom['matriks'],
                    'dosen_pembimbing_id' => null,
                ];
            }
        }

        foreach ($hasilAkhir as $data) {
            $rekom = RekomendasiLombaModel::where('lomba_id', $data['lomba_id'])
                ->where('mahasiswa_nim', $data['nim'])
                ->first();

            if ($rekom) {
                // Hanya update skor jika statusnya masih Pending
                if ($rekom->status == 'Pending') {
                    $rekom->update([
                        'skor' => $data['skor'],
                    ]);
                }
            } else {
                // Buat rekomendasi baru dengan status Pending
                RekomendasiLombaModel::create([
                    'lomba_id' => $data['lomba_id'],
                    'mahasiswa_nim' => $data['nim'],
                    'skor' => $data['skor'],
                    'status' => 'Pending',
                    'dosen_pembimbing_id' => null,
                ]);
            }
        }

        return response()->json($hasilAkhir);
    }
}
