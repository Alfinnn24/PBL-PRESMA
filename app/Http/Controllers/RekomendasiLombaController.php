<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\PendaftaranLombaModel;
use App\Models\RekomendasiLombaModel;
use App\Models\DosenPembimbingModel;
use App\Services\TopsisSpkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RekomendasiLombaController extends Controller
{
    protected $topsisService;
    public function __construct(TopsisSpkService $rekomendasiService)
    {
        $this->topsisService = $rekomendasiService;
    }
    // Menampilkan rekomendasi lomba yang sesuai dengan mahasiswa/dosen
    public function index(Request $request)
    {
        $user = auth()->user();

        $this->topsisService->prosesSemuaLombaDenganTopsis();

        $breadcrumb = (object) [
            'title' => 'Data Lomba',
            'list' => ['Lomba', 'Rekomendasi']
        ];

        $page = (object) [
            'title' => 'Daftar lomba dengan skor kecocokan'
        ];

        $activeMenu = 'rekomendasiLomba';

        // Ambil daftar nama lomba (jika perlu filter)
        $namaLomba = LombaModel::select('nama')->distinct()->get();

        $rekomendasi = RekomendasiLombaModel::with(['mahasiswa', 'lomba']);

        if ($user->role == 'mahasiswa') {
            return view('rekomendasi.index', compact('breadcrumb', 'page', 'activeMenu', 'namaLomba', 'rekomendasi'));
        } elseif ($user->role == 'admin') {
            return view('admin.rekomendasi.index', compact('breadcrumb', 'page', 'activeMenu', 'namaLomba', 'rekomendasi'));
        } elseif ($user->role == 'dosen') {
            return view('rekomendasi.indexDosen', compact('breadcrumb', 'page', 'activeMenu', 'namaLomba', 'rekomendasi'));
        }
    }

    public function list(Request $request)
    {
        $user = auth()->user();

        $rekomendasi = RekomendasiLombaModel::with(['mahasiswa', 'lomba.bidangKeahlian', 'dosen']);

        if ($user->role == 'mahasiswa') {
            $rekomendasi->where('mahasiswa_nim', $user->mahasiswa->nim)
                ->orderBy('skor', 'desc');
        } elseif ($user->role == 'dosen') {
            $ids = RekomendasiLombaModel::where('dosen_pembimbing_id', $user->dosen->id)
                ->groupBy('lomba_id')
                ->selectRaw('MAX(id) as id')
                ->pluck('id');

            $rekomendasi->whereIn('id', $ids);
        }

        // Filter berdasarkan status
        if ($request->status) {
            if ($user->role == 'dosen') {
                if ($request->status == 'pending') {
                    $rekomendasi->whereNull('status_dosen');
                } else {
                    $rekomendasi->where('status_dosen', $request->status);
                }
            } else {
                $rekomendasi->where('status', $request->status);
            }
        }

        // Filter berdasarkan kecocokan
        if ($request->kecocokan) {
            if ($request->kecocokan == 'tinggi') {
                $rekomendasi->where('skor', '>=', 0.85);
            } elseif ($request->kecocokan == 'sedang') {
                $rekomendasi->whereBetween('skor', [0.7, 0.84]);
            } elseif ($request->kecocokan == 'rendah') {
                $rekomendasi->whereBetween('skor', [0.49, 0.69]);
            } elseif ($request->kecocokan == 'srendah') {
                $rekomendasi->where('skor', '<', 0.4);
            }
        }

        // Searchable untuk dosen
        if ($user->role == 'dosen' && $request->has('search') && $request->search['value']) {
            $search = strtolower($request->search['value']);
            $rekomendasi->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"]);
                })
                    ->orWhereHas('lomba', function ($q2) use ($search) {
                        $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(penyelenggara) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(tingkat) LIKE ?', ["%{$search}%"]);
                    })
                    ->orWhereHas('lomba.bidangKeahlian', function ($q2) use ($search) {
                        $q2->whereRaw('LOWER(keahlian) LIKE ?', ["%{$search}%"]);
                    })
                    ->orWhereRaw('LOWER(status) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(status_dosen) LIKE ?', ["%{$search}%"]);
            });
        }

        return DataTables::of($rekomendasi)
            ->addIndexColumn()
            ->addColumn('mahasiswa', fn($d) => $d->mahasiswa->nama_lengkap ?? '-')
            ->addColumn('lomba', fn($d) => $d->lomba->nama ?? '-')
            ->addColumn('status', fn($d) => ucfirst($d->status))
            ->addColumn('hasil_rekomendasi', fn($d) => $this->getHasilRekomendasi($d->skor))
            ->addColumn('kategori', fn($d) => $d->lomba->bidangKeahlian->keahlian ?? '-')
            ->addColumn('penyelenggara', fn($d) => $d->lomba->penyelenggara ?? '-')
            ->addColumn('tingkat', fn($d) => $d->lomba->tingkat ?? '-')
            ->addColumn('statusDosen', fn($d) => ucfirst($d->status_dosen ?? 'Pending'))
            ->addColumn('aksi', fn($d) => $this->getAksiColumn($d, $user))
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function listAll(Request $request)
    {
        $user = auth()->user();
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $draw = intval($request->get('draw', 1));

        $filterStatus = $request->get('status');
        $filterKecocokan = $request->get('kecocokan');
        $searchValue = strtolower($request->input('search.value')); // Search dari DataTables

        $lombaList = LombaModel::with(['rekomendasi.mahasiswa', 'rekomendasi.dosen'])->get();
        $allLombaPeserta = collect();

        foreach ($lombaList as $lomba) {
            $rekomendasi = $lomba->rekomendasi;

            if ($filterStatus) {
                $rekomendasi = $rekomendasi->filter(fn($item) => strtolower($item->status) == strtolower($filterStatus));
            }
            if ($filterKecocokan) {
                $rekomendasi = $rekomendasi->filter(function ($item) use ($filterKecocokan) {
                    $skor = $item->skor;
                    return match ($filterKecocokan) {
                        'tinggi' => $skor >= 0.85,
                        'sedang' => $skor >= 0.7 && $skor <= 0.84,
                        'rendah' => $skor >= 0.4 && $skor <= 0.69,
                        'srendah' => $skor < 0.4,
                        default => true,
                    };
                });
            }

            $disetujui = $rekomendasi->where('status', 'Disetujui')->sortByDesc('skor');
            $belumDisetujui = $rekomendasi->where('status', '!=', 'Ditolak')->sortByDesc('skor');

            $totalNeeded = $lomba->jumlah_peserta;
            $selected = $disetujui->take($totalNeeded);
            $remaining = $totalNeeded - $selected->count();
            if ($remaining > 0) {
                $selected = $selected->merge($belumDisetujui->take($remaining));
            }

            $allLombaPeserta->push([
                'lomba' => $lomba,
                'peserta' => $selected
            ]);
        }

        $flatList = collect();
        foreach ($allLombaPeserta as $lombaData) {
            $rowspan = $lombaData['peserta']->count();
            foreach ($lombaData['peserta'] as $i => $item) {
                $flatList->push([
                    'rowspan' => $i == 0 ? $rowspan : 0,
                    'lomba' => $item->lomba->nama ?? '-',
                    'mahasiswa' => $item->mahasiswa->nama_lengkap ?? '-',
                    'status' => ucfirst($item->status),
                    'hasil_rekomendasi' => $this->getHasilRekomendasi($item->skor),
                    'dosen' => $item->dosen ? $item->dosen->nama_lengkap : 'Belum ditentukan',
                    'aksi' => $this->getAksiColumn($item, $user),
                ]);
            }
        }

        // ✅ Filter berdasarkan pencarian global (search.value)
        if (!empty($searchValue)) {
            $flatList = $flatList->filter(function ($item) use ($searchValue) {
                return str_contains(strtolower($item['lomba']), $searchValue) ||
                    str_contains(strtolower($item['mahasiswa']), $searchValue) ||
                    str_contains(strtolower($item['status']), $searchValue) ||
                    str_contains(strtolower($item['hasil_rekomendasi']), $searchValue) ||
                    str_contains(strtolower($item['dosen']), $searchValue);
            })->values();
        }

        // Urutkan jika diperlukan
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        $orderColumnName = $request->input("columns.$orderColumnIndex.data");

        if ($orderColumnName && in_array($orderColumnName, ['lomba', 'mahasiswa', 'status', 'hasil_rekomendasi', 'dosen'])) {
            $flatList = $flatList->sortBy(function ($item) use ($orderColumnName) {
                return strtolower($item[$orderColumnName] ?? '');
            }, SORT_REGULAR, $orderDir === 'desc')->values();
        }

        $totalRecords = $flatList->count();

        $pagedData = $flatList->slice($start, $length)->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $pagedData,
        ]);
    }


    // fungsi tambahan
    private function getHasilRekomendasi($skor)
    {
        if ($skor >= 0.85)
            return 'Sangat Direkomendasikan';
        elseif ($skor >= 0.7)
            return 'Direkomendasikan';
        elseif ($skor >= 0.4)
            return 'Cukup Direkomendasikan';
        else
            return 'Tidak Direkomendasikan';
    }

    private function getAksiColumn($data, $user)
    {
        if ($user->role === 'admin') {
            $disabledApproved = ($data->status_dosen == 'Disetujui') ? 'disabled' : '';
            return '
        <button class="btn btn-sm btn-info" onclick="modalAction(\'' . url('/rekomendasi/' . $data->id . '/show_ajax') . '\')">Detail</button>
        <button class="btn btn-sm btn-warning" onclick="modalAction(\'' . url('/rekomendasi/' . $data->id . '/edit_ajax') . '\')"' . $disabledApproved . '>Edit</button>
        <button class="btn btn-sm btn-danger" onclick="modalAction(\'' . url('/rekomendasi/' . $data->id . '/delete_ajax') . '\')">Hapus</button>';
        } elseif ($user->role === 'dosen') {
            $disabledApproved = ($data->status_dosen == 'Disetujui') ? 'disabled' : '';
            return '
        <button class="btn btn-sm btn-info" onclick="modalAction(\'' . url('/rekomendasi/' . $data->lomba_id . '/show_ajax') . '\')">Detail</button>
        <button class="btn btn-sm btn-success" onclick="ubahStatusDosen(' . $data->id . ', \'approve\')"' . $disabledApproved . '>Setujui</button>
        <button class="btn btn-sm btn-danger" onclick="ubahStatusDosen(' . $data->id . ', \'reject\')"' . $disabledApproved . '>Tolak</button>';
        } else {
            $disabledApprove = ($data->status == 'Disetujui' || RekomendasiLombaModel::where('lomba_id', $data->lomba_id)->where('status', 'Disetujui')->count() >= $data->lomba->jumlah_peserta) ? 'disabled' : '';
            $tolakApprove = ($data->status == 'Ditolak') || RekomendasiLombaModel::where('lomba_id', $data->lomba_id)->where('status', 'Disetujui')->count() >= $data->lomba->jumlah_peserta ? 'disabled' : '';
            return '
        <button class="btn btn-sm btn-info" onclick="modalAction(\'' . url('/rekomendasi/' . $data->lomba_id . '/show_ajax') . '\')">Detail</button>
        <button class="btn btn-sm btn-success" onclick="buatPendaftaran(' . $data->lomba_id . ')" ' . $disabledApprove . '>Daftar</button>
        <button class="btn btn-sm btn-danger" onclick="ubahStatus(' . $data->id . ', \'reject\')" ' . $tolakApprove . '>Tolak</button>';
        }
    }



    public function show_ajax(string $id)
    {
        $user = auth()->user();

        if ($user->role == 'mahasiswa' || $user->role == 'dosen') {
            $lomba = LombaModel::with('creator.mahasiswa', 'creator.dosen', 'creator.admin')->find($id);
            return view('rekomendasi.show_ajax', ['lomba' => $lomba]);
        } elseif ($user->role == 'admin') {
            $rekomendasi = RekomendasiLombaModel::with([
                'mahasiswa.sertifikasis',
                'mahasiswa.bidangKeahlian',
                'mahasiswa.pengalaman',
                'mahasiswa.prestasi',
                'mahasiswa.prestasi.prestasi.lomba',
                'dosen.bidangMinat.bidangMinat',
            ])->findOrFail($id);
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

        // Cari rekomendasi berdasarkan id
        $rekomendasi = RekomendasiLombaModel::findOrFail($id);

        // Ambil lomba_id dari rekomendasi tersebut
        $lombaId = $rekomendasi->lomba_id;

        // Update semua rekomendasi yang punya lomba_id sama
        RekomendasiLombaModel::where('lomba_id', $lombaId)
            ->update(['dosen_pembimbing_id' => $request->dosen_id]);

        return response()->json([
            'status' => true,
            'message' => 'Data dosen pembimbing untuk seluruh peserta lomba berhasil diperbarui.'
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
            // Hapus data dosen pembimbing yang terkait dengan mahasiswa_nim
            DosenPembimbingModel::where('mahasiswa_nim', $rekomendasi->mahasiswa_nim)
                ->where('dosen_id', $rekomendasi->dosen_pembimbing_id)
                ->delete();
            PendaftaranLombaModel::where('mahasiswa_nim', $rekomendasi->mahasiswa_nim)
                ->where('lomba_id', $rekomendasi->lomba_id)
                ->delete();

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

        // Tambahkan ke dosen_pembimbing jika dosen juga sudah menyetujui
        if ($rekomendasi->status_dosen === 'Disetujui') {
            $exists = \DB::table('dosen_pembimbing')
                ->where('dosen_id', $rekomendasi->dosen_pembimbing_id)
                ->where('mahasiswa_nim', $rekomendasi->mahasiswa_nim)
                ->exists();

            if (!$exists) {
                \DB::table('dosen_pembimbing')->insert([
                    'dosen_id' => $rekomendasi->dosen_pembimbing_id,
                    'mahasiswa_nim' => $rekomendasi->mahasiswa_nim,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Rekomendasi lomba disetujui']);
    }


    public function reject(Request $request, string $id)
    {
        $rekomendasi = RekomendasiLombaModel::find($id);
        $rekomendasi->status = 'Ditolak';
        $rekomendasi->save();

        return response()->json(['status' => 'success', 'message' => 'Rekomendasi lomba ditolak']);
    }

    public function approveDosen(Request $request, string $id)
    {
        $rekomendasi = RekomendasiLombaModel::findOrFail($id);

        // Update status dosen untuk semua rekomendasi lomba ini
        RekomendasiLombaModel::where('lomba_id', $rekomendasi->lomba_id)
            ->update(['status_dosen' => 'Disetujui']);

        // Ambil semua rekomendasi lomba dengan status mahasiswa 'Disetujui'
        $disetujuiMahasiswa = RekomendasiLombaModel::where('lomba_id', $rekomendasi->lomba_id)
            ->where('status', 'Disetujui')
            ->get();

        foreach ($disetujuiMahasiswa as $item) {
            $exists = \DB::table('dosen_pembimbing')
                ->where('dosen_id', $item->dosen_pembimbing_id)
                ->where('mahasiswa_nim', $item->mahasiswa_nim)
                ->exists();

            if (!$exists) {
                \DB::table('dosen_pembimbing')->insert([
                    'dosen_id' => $item->dosen_pembimbing_id,
                    'mahasiswa_nim' => $item->mahasiswa_nim,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Semua rekomendasi pada lomba ini telah disetujui oleh dosen']);
    }


    public function rejectDosen(Request $request, string $id)
    {
        $rekomendasi = RekomendasiLombaModel::findOrFail($id);

        RekomendasiLombaModel::where('lomba_id', $rekomendasi->lomba_id)
            ->update([
                'status_dosen' => 'Pending',
                'dosen_pembimbing_id' => null
            ]);

        return response()->json(['status' => 'success', 'message' => 'Semua rekomendasi pada lomba ini telah ditolak oleh dosen']);
    }


    public function getDetail($id)
    {
        $dosen = DosenModel::with(['programStudi', 'bidangMinat.bidangMinat'])->find($id);
        if ($dosen) {
            return response()->json([
                'status' => true,
                'data' => [
                    'nidn' => $dosen->nidn,
                    'program_studi' => $dosen->programStudi->nama_prodi ?? '-',
                    'no_telp' => $dosen->no_telp,
                    'bidang_minat' => $dosen->bidangMinat->pluck('bidangMinat.bidang_minat')->implode(', ') ?: '-',
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
