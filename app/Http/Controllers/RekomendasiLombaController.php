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
                $rekomendasi->where('skor', '>=', 0.85);
            } elseif ($request->kecocokan == 'sedang') {
                $rekomendasi->whereBetween('skor', [0.7, 0.84]);
            } elseif ($request->kecocokan == 'rendah') {
                $rekomendasi->whereBetween('skor', [0.5, 0.69]);
            } elseif ($request->kecocokan == 'srendah') {
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
            ->addColumn('hasil_rekomendasi', function ($data) {
                $skor = $data->skor;
                if ($skor >= 0.85) {
                    return 'Sangat Direkomendasikan';
                } elseif ($skor >= 0.7) {
                    return 'Direkomendasikan';
                } elseif ($skor >= 0.4) {
                    return 'Cukup Direkomendasikan';
                } else {
                    return 'Tidak Direkomendasikan';
                }
            })
            ->addColumn('aksi', function ($data) {
                $detailUrl = url('/rekomendasi/' . $data->lomba_id . '/show_ajax');

                $disabledApprove = '';
                $tolakApprove = '';

                // Cek jika status sudah 'approve'
                if ($data->status == 'Disetujui') {
                    $disabledApprove = 'disabled';
                }

                if ($data->status == 'Ditolak') {
                    $tolakApprove = 'disabled';
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
                    <button class="btn btn-sm btn-danger" onclick="ubahStatus(' . $data->id . ', \'reject\') " ' . $tolakApprove . '>
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

    public function approve(Request $request, string $id)
    {
        $rekomendasi = RekomendasiLombaModel::find($id);
        $rekomendasi->status = 'Disetujui';
        $rekomendasi->save();

        return response()->json(['status' => 'success', 'message' => 'Rekomendasi lomba disetujui']);
    }
    public function reject(Request $request, string $id)
    {
        $rekomendasi = RekomendasiLombaModel::find($id);
        $rekomendasi->status = 'Ditolak';
        $rekomendasi->save();

        return response()->json(['status' => 'success', 'message' => 'Rekomendasi lomba ditolak']);
    }
}
