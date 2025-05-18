<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\RekomendasiLombaModel;
use App\Services\FuzzySpkService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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

        $breadcrumb = (object) [
            'title' => 'Rekomendasi Lomba',
            'list' => ['Lomba', 'Rekomendasi']
        ];

        $page = (object) [
            'title' => 'Daftar rekomendasi lomba berdasarkan peran pengguna'
        ];

        $activeMenu = 'rekomendasiLomba';

        // Ambil daftar nama lomba (jika perlu filter)
        $namaLomba = LombaModel::select('nama')->distinct()->get();

        $rekomendasi = RekomendasiLombaModel::with(['mahasiswa', 'lomba']);

        return view('rekomendasi.index', compact('breadcrumb', 'page', 'activeMenu', 'namaLomba', 'rekomendasi'));
    }

    public function list(Request $request)
    {
        $user = auth()->user();

        $rekomendasi = RekomendasiLombaModel::with(['mahasiswa', 'lomba']);

        if ($user->role == 'mahasiswa') {
            $rekomendasi->where('mahasiswa_nim', $user->mahasiswa->nim);
            $rekomendasi->orderBy('skor', 'desc');
        } elseif ($user->role == 'dosen') {
            $rekomendasi->where('dosen_pembimbing_id', $user->dosen->id);
        }
        // jika admin, tidak ada filter (melihat semua)

        // Filter berdasarkan nama lomba
        if ($request->nama_lomba) {
            $rekomendasi->whereHas('lomba', function ($query) use ($request) {
                $query->where('nama', $request->nama_lomba);
            });
        }

        return DataTables::of($rekomendasi)
            ->addIndexColumn()
            ->addColumn('mahasiswa', function ($data) {
                return $data->mahasiswa->nama ?? '-';
            })
            ->addColumn('lomba', function ($data) {
                return $data->lomba->nama ?? '-';
            })
            ->addColumn('status', function ($data) {
                return ucfirst($data->status);
            })
            ->addColumn('aksi', function ($data) {
                return '
                    <button class="btn btn-sm btn-success" onclick="ubahStatus(' . $data->id . ', \'approve\')">
                        <i class="fas fa-check-circle"></i> Setujui
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="ubahStatus(' . $data->id . ', \'reject\')">
                        <i class="fas fa-times-circle"></i> Tolak
                    </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
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

        $excludedMahasiswa = RekomendasiLombaModel::where('lomba_id', $lomba->id)->pluck('mahasiswa_nim')->toArray();
        $excludedDosen = RekomendasiLombaModel::where('lomba_id', $lomba->id)->pluck('dosen_pembimbing_id')->filter()->toArray();

        $hasilPengganti = $this->fuzzySpkService->prosesRekomendasi($lomba, $excludedMahasiswa, $excludedDosen);
        //dd($hasilPengganti);

        foreach ($hasilPengganti as $pengganti) {
            $mahasiswa = MahasiswaModel::where('nama_lengkap', $pengganti['mahasiswa'])->first();
            if ($mahasiswa) {
                $this->createRekomendasi($mahasiswa, $lomba);
            }
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
