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
            $rekomendasi->where('mahasiswa_nim', $user->mahasiswa->nim)
                ->orderBy('skor', 'desc');
        } elseif ($user->role == 'dosen') {
            $rekomendasi->where('dosen_pembimbing_id', $user->dosen->id);
        }

        // Filter berdasarkan status
        if ($request->status) {
            $rekomendasi->where('status', $request->status);
        }

        // Filter berdasarkan kecocokan (tinggi/sedang/rendah)
        if ($request->kecocokan) {
            if ($request->kecocokan == 'tinggi') {
                $rekomendasi->where('skor', '>=', 0.7);
            } elseif ($request->kecocokan == 'sedang') {
                $rekomendasi->whereBetween('skor', [0.4, 0.699]);
            } elseif ($request->kecocokan == 'rendah') {
                $rekomendasi->where('skor', '<', 0.4);
            }
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
                $detailUrl = url('/rekomendasi/' . $data->lomba_id . '/show_ajax');

                $disabledApprove = '';

                // Cek jika status sudah 'approve'
                if ($data->status == 'Disetujui') {
                    $disabledApprove = 'disabled';
                }

                // Hitung jumlah peserta yang sudah disetujui pada lomba ini
                $jumlahDisetujui = RekomendasiLombaModel::where('lomba_id', $data->lomba_id)
                    ->where('status', 'Disetujui')
                    ->count();

                // Jika jumlah peserta yang disetujui sudah sama atau lebih dari kuota
                if ($jumlahDisetujui >= $data->lomba->jumlah_peserta) {
                    $disabledApprove = 'disabled';
                }

                return '
                    <button class="btn btn-sm btn-primary" onclick="modalAction(\'' . $detailUrl . '\')">
                        <i class="fas fa-search"></i> Detail
                    </button>
                    <button class="btn btn-sm btn-success" onclick="ubahStatus(' . $data->id . ', \'approve\')" ' . $disabledApprove . '>
                        <i class="fas fa-check-circle"></i> Setujui
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="ubahStatus(' . $data->id . ', \'reject\')">
                        <i class="fas fa-times-circle"></i> Tolak
                    </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $lomba = LombaModel::with('creator.mahasiswa', 'creator.dosen', 'creator.admin')->find($id);
        return view('rekomendasi.show_ajax', ['lomba' => $lomba]);
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
