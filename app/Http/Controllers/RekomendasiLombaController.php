<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\RekomendasiLombaModel;
use App\Services\FuzzySpkService;
use Illuminate\Http\Request;

class RekomendasiLombaController extends Controller
{
    protected $fuzzySpkService;

    public function __construct(FuzzySpkService $fuzzySpkService)
    {
        $this->fuzzySpkService = $fuzzySpkService;
    }

    // Menampilkan rekomendasi lomba yang sesuai dengan mahasiswa/dosen
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeMenu = 'rekomendasiLomba';
        $breadcrumb = (object) [
            'title' => 'Rekomendasi Lomba',
            'list' => ['Lomba', 'Rekomendasi']
        ];

        if ($user->role == 'mahasiswa') {
            $rekomendasi = RekomendasiLombaModel::where('mahasiswa_nim', $user->mahasiswa->nim)->get();
        } elseif ($user->role == 'dosen') {
            $rekomendasi = RekomendasiLombaModel::where('dosen_pembimbing_id', $user->dosen->id)->get();
        } else {
            return redirect()->back()->with('error', 'Role tidak terdaftar');
        }

        return view('rekomendasi.index', compact('rekomendasi', 'activeMenu', 'breadcrumb'));
    }

    // Proses untuk menyetujui atau menolak rekomendasi lomba
    public function updateStatus(Request $request, $id)
    {
        $rekomendasi = RekomendasiLombaModel::findOrFail($id);

        // Validasi status yang dipilih
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        // Mengupdate status rekomendasi lomba
        $rekomendasi->status = $request->status;
        $rekomendasi->save();

        // Jika ada yang menolak, carikan pengganti
        if ($request->status == 'Ditolak') {
            $this->cariPengganti($rekomendasi);
        }

        return redirect()->route('rekomendasi.index')->with('success', 'Status rekomendasi berhasil diperbarui');
    }

    // Fungsi untuk mencari pengganti jika ada yang menolak
    private function cariPengganti(RekomendasiLombaModel $rekomendasi)
    {
        $lomba = $rekomendasi->lomba;

        // Cari pengganti mahasiswa yang memenuhi kriteria
        $penggantiMahasiswa = MahasiswaModel::with(['sertifikasis', 'bidangKeahlian', 'pengalaman', 'prestasi', 'dosenPembimbing'])
            ->whereDoesntHave('rekomendasi', function ($query) use ($lomba) {
                $query->where('lomba_id', $lomba->id);
            })
            ->get();

        $hasilPengganti = $this->fuzzySpkService->prosesRekomendasi($lomba);

        // Pilih mahasiswa dan dosen pengganti yang sesuai dan buat rekomendasi baru
        foreach ($hasilPengganti as $pengganti) {
            $this->createRekomendasi($pengganti['mahasiswa'], $lomba);
        }
    }

    // Membuat rekomendasi baru untuk mahasiswa dan dosen pengganti
    private function createRekomendasi($mahasiswa, LombaModel $lomba)
    {
        $dosen = $mahasiswa->dosenPembimbing->first()?->dosen;

        RekomendasiLombaModel::create([
            'mahasiswa_nim' => $mahasiswa->nim,
            'lomba_id' => $lomba->id,
            'dosen_pembimbing_id' => $dosen?->id,
            'status' => 'Disetujui',
        ]);
    }
}
