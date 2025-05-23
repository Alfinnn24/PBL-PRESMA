<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\RekomendasiLombaModel;
use App\Services\FuzzySpkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        if ($user->role == 'mahasiswa' || $user->role == 'dosen') {
            return view('rekomendasi.index', compact('breadcrumb', 'page', 'activeMenu', 'namaLomba', 'rekomendasi'));
        } elseif ($user->role == 'admin') {
            return view('admin.rekomendasi.index', compact('breadcrumb', 'page', 'activeMenu', 'namaLomba', 'rekomendasi'));
        }
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

        // Filter berdasarkan kecocokan
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
                return $data->mahasiswa->nama_lengkap ?? '-';
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
            ->addColumn('aksi', function ($data) use ($user) {
                if ($user->role === 'admin') {
                    $editUrl = url('/rekomendasi/' . $data->id . '/edit_ajax');
                    $deleteUrl = url('/rekomendasi/' . $data->id . '/delete_ajax');
                    $detailUrl = url('/rekomendasi/' . $data->lomba_id . '/show_ajax');

                    return '
                    <button class="btn btn-sm btn-info" onclick="modalAction(\'' . $detailUrl . '\')">
                       Detail
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="modalAction(\'' . $editUrl . '\')">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="modalAction(\'' . $deleteUrl . '\')">
                        Hapus
                    </button>';
                } else {
                    $detailUrl = url('/rekomendasi/' . $data->lomba_id . '/show_ajax');

                    $disabledApprove = '';
                    $tolakApprove = '';

                    if ($data->status == 'Disetujui') {
                        $disabledApprove = 'disabled';
                    }

                    if ($data->status == 'Ditolak') {
                        $tolakApprove = 'disabled';
                    }

                    $jumlahDisetujui = RekomendasiLombaModel::where('lomba_id', $data->lomba_id)
                        ->where('status', 'Disetujui')
                        ->count();

                    if ($jumlahDisetujui >= $data->lomba->jumlah_peserta) {
                        $disabledApprove = 'disabled';
                    }

                    return '
                    <button class="btn btn-sm btn-info" onclick="modalAction(\'' . $detailUrl . '\')">
                        Detail
                    </button>
                    <button class="btn btn-sm btn-success" onclick="ubahStatus(' . $data->id . ', \'approve\')" ' . $disabledApprove . '>
                        Setujui
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="ubahStatus(' . $data->id . ', \'reject\')" ' . $tolakApprove . '>
                        Tolak
                    </button>';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $user = auth()->user();

        if ($user->role == 'mahasiswa' || $user->role == 'dosen') {
            $lomba = LombaModel::with('creator.mahasiswa', 'creator.dosen', 'creator.admin')->find($id);
            return view('rekomendasi.show_ajax', ['lomba' => $lomba]);
        } elseif ($user->role == 'admin') {
            $rekomendasi = RekomendasiLombaModel::with(['mahasiswa', 'lomba', 'dosen'])->find($id);
            $lomba = LombaModel::with('creator.mahasiswa', 'creator.dosen', 'creator.admin')->find($rekomendasi->lomba_id);
            return view('admin.rekomendasi.show_ajax', compact('rekomendasi', 'lomba'));
        }
    }

    public function edit_ajax($id)
    {
        $rekomendasi = RekomendasiLombaModel::findOrFail($id);
        $daftarDosen = DosenModel::with('programStudi')->get();
        return view('admin.rekomendasi.edit_ajax', compact('rekomendasi', 'daftarDosen'));
    }

    public function update_ajax(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'dosen_id' => 'required|exists:dosen,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $rekomendasi = RekomendasiLombaModel::findOrFail($id);
        $rekomendasi->dosen_pembimbing_id = $request->dosen_id;
        $rekomendasi->save();

        return response()->json([
            'status' => true,
            'message' => 'Data dosen berhasil diperbarui.'
        ]);
    }


    public function confirm_ajax($id)
    {
        $rekomendasi = RekomendasiLombaModel::with(['lomba', 'mahasiswa', 'dosen'])->findOrFail($id);
        return view('admin.rekomendasi.confirm_ajax', compact('rekomendasi'));
    }

    public function delete_ajax($id)
    {
        $rekomendasi = RekomendasiLombaModel::find($id);

        if (!$rekomendasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        try {
            $rekomendasi->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data. Mungkin masih terkait data lain.'
            ]);
        }
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

    public function getDetail($id)
    {
        $dosen = DosenModel::with('programStudi')->find($id);
        if ($dosen) {
            return response()->json([
                'status' => true,
                'data' => [
                    'nidn' => $dosen->nidn,
                    'program_studi' => $dosen->programStudi->nama_prodi ?? '-',
                    'no_telp' => $dosen->no_telp,
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Dosen tidak ditemukan'
            ]);
        }
    }

}
