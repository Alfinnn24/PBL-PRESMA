<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\PrestasiModel;
use App\Models\MahasiswaModel;
use App\Models\DetailPrestasiModel;
use App\Models\PeriodeModel;
use App\Models\BidangKeahlianModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PrestasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $breadcrumb = (object) [
            'title' => 'Data Prestasi',
            'list' => ['Home', 'Prestasi']
        ];

        $page = (object) [
            'title' => 'Daftar Prestasi Mahasiswa'
        ];

        $activeMenu = 'verifprestasi';

        $listPeriode = PeriodeModel::all();
        $listKeahlian = BidangKeahlianModel::all();

        if ($user->role === 'admin') {
            return view('admin.prestasi.index', compact('breadcrumb', 'page', 'activeMenu', 'listPeriode', 'listKeahlian'));
        } else {
            return view('prestasi.index', compact('breadcrumb', 'page', 'activeMenu', 'listPeriode', 'listKeahlian'));
        }
    }

    public function list(Request $request)
    {
        $user = auth()->user(); // mendapatkan user yang login

        // Ambil filter dari request
        $status = $request->status;
        $periode = $request->periode;
        $keahlian = $request->keahlian;

        if ($user->role === 'admin') {
            $data = PrestasiModel::with(['lomba', 'detailPrestasi.mahasiswa'])
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                })
                ->when($periode, function ($query) use ($periode) {
                    $query->whereHas('lomba', function ($q) use ($periode) {
                        $q->where('periode_id', $periode);
                    });
                })
                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->whereHas('lomba', function ($q) use ($keahlian) {
                        $q->where('bidang_keahlian_id', $keahlian);
                    });
                })
                ->get()
                ->filter(function ($item) {
                    return $item->detailPrestasi->count() > 0;
                });
        } else {
            // Mahasiswa hanya melihat data dirinya
            $mahasiswa = $user->mahasiswa;
            $data = PrestasiModel::with(['detailPrestasi.mahasiswa'])
                ->whereHas('detailPrestasi.mahasiswa', function ($query) use ($mahasiswa) {
                    $query->where('nim', $mahasiswa->nim);
                })
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                })
                ->when($periode, function ($query) use ($periode) {
                    $query->whereHas('lomba', function ($q) use ($periode) {
                        $q->where('periode_id', $periode);
                    });
                })
                ->when($keahlian, function ($query) use ($keahlian) {
                    $query->whereHas('lomba', function ($q) use ($keahlian) {
                        $q->where('bidang_keahlian_id', $keahlian);
                    });
                })
                ->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('jumlah_mahasiswa', function ($row) {
                return $row->detailPrestasi->count();
            })
            ->addColumn('action', function ($row) use ($user) {
                $btn = '<button onclick="modalAction(\'' . url('/prestasi/' . $row->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';

                if ($user->role == 'admin') {
                    $btn .= '<button onclick="ubahStatus(' . $row->id . ', \'approve\')" class="btn btn-success btn-sm">Setujui</button> ';
                    $btn .= '<button onclick="ubahStatus(' . $row->id . ', \'reject\')" class="btn btn-danger btn-sm">Tolak</button>';
                } else {
                    $btn .= '<button onclick="modalAction(\'' . url('/prestasi/' . $row->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/prestasi/' . $row->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create_ajax()
    {
        $user = auth()->user();

        $mahasiswa = MahasiswaModel::all();
        $lomba = LombaModel::all();

        if ($user->role === 'admin') {
            return view('admin.prestasi.create_ajax', compact('mahasiswa', 'lomba'));
        } else {
            return view('prestasi.create_ajax', compact('mahasiswa', 'lomba', 'user'));
        }
    }

    public function store_ajax(Request $request)
    {
        $user = Auth::user();

        // Pastikan input mahasiswa_nim menjadi array
        $mahasiswaNimInput = $request->input('mahasiswa_nim');
        $mahasiswaNimArray = is_array($mahasiswaNimInput)
            ? $mahasiswaNimInput
            : [$mahasiswaNimInput];

        // Validasi input
        $validator = Validator::make([
            'nama_prestasi' => $request->input('nama_prestasi'),
            'lomba_id' => $request->input('lomba_id'),
            'file_bukti' => $request->file('file_bukti'),
            'catatan' => $request->input('catatan'),
            'mahasiswa_nim' => $mahasiswaNimArray,
        ], [
            'nama_prestasi' => 'required|string|max:255',
            'lomba_id' => 'required|exists:lomba,id',
            'file_bukti' => 'required|file|mimetypes:application/pdf,image/jpeg,image/png,image/jpg,image/webp',
            'catatan' => 'nullable|string',
            'mahasiswa_nim' => 'required|array',
            'mahasiswa_nim.*' => 'exists:mahasiswa,nim',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Proses upload file
        $pathBukti = null;
        if ($request->hasFile('file_bukti')) {
            $file = $request->file('file_bukti');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $pathBukti = 'uploads/prestasi/' . $namaFile;
            $file->move(public_path('uploads/prestasi'), $namaFile);
        }

        // Status otomatis berdasarkan role user
        $status = $user->role == 'admin' ? 'Disetujui' : 'Pending';

        // Simpan ke tabel prestasi
        $prestasi = PrestasiModel::create([
            'nama_prestasi' => $request->nama_prestasi,
            'lomba_id' => $request->lomba_id,
            'file_bukti' => $pathBukti,
            'status' => $status,
            'catatan' => $request->catatan ?? '',
            'created_by' => $user->id
        ]);

        // Simpan relasi mahasiswa ke tabel detail_prestasi
        foreach ($mahasiswaNimArray as $nim) {
            DetailPrestasiModel::create([
                'prestasi_id' => $prestasi->id,
                'mahasiswa_nim' => $nim
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Prestasi berhasil ditambahkan'
        ]);
    }


    public function show_ajax($id)
    {
        $user = auth()->user();
        $prestasi = PrestasiModel::with(['lomba', 'detailPrestasi.mahasiswa', 'creator.mahasiswa', 'creator.dosen', 'creator.admin'])->findOrFail($id);
        if ($user->role === 'admin') {
            return view('admin.prestasi.show_ajax', compact('prestasi'));
        } else {
            return view('prestasi.show_ajax', compact('prestasi'));
        }
    }


    public function edit_ajax($id)
    {
        $lomba = LombaModel::all();
        $prestasi = PrestasiModel::with(['lomba.bidangKeahlian', 'detailPrestasi'])->findOrFail($id);
        $mahasiswa = MahasiswaModel::all();
        return view('prestasi.edit_ajax', compact('prestasi', 'mahasiswa', 'lomba'));
    }

    public function update_ajax(Request $request, $id)
    {
        $user = Auth::user();
        $prestasi = PrestasiModel::find($id);

        // if (!$prestasi || $prestasi->created_by != $user->id) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Data tidak ditemukan atau akses ditolak.'
        //     ]);
        // }

        $validator = Validator::make([
            'nama_prestasi' => $request->input('nama_prestasi'),
            'lomba_id' => $request->input('lomba_id'),
            'file_bukti' => $request->file('file_bukti'),
            'catatan' => $request->input('catatan'),
        ], [
            'nama_prestasi' => 'required|string|max:255',
            'lomba_id' => 'required|exists:lomba,id',
            'file_bukti' => 'nullable|file|mimetypes:application/pdf,image/jpeg,image/png,image/jpg,image/webp',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Handle file upload jika ada
        if ($request->hasFile('file_bukti')) {
            $file = $request->file('file_bukti');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $pathBukti = 'uploads/prestasi/' . $namaFile;
            $file->move(public_path('uploads/prestasi'), $namaFile);
            $prestasi->file_bukti = $pathBukti;
        }

        $prestasi->nama_prestasi = $request->input('nama_prestasi');
        $prestasi->lomba_id = $request->input('lomba_id');
        $prestasi->catatan = $request->input('catatan');
        $prestasi->status = 'Pending'; // Reset status ke pending untuk update mahasiswa
        $prestasi->save();

        return response()->json([
            'status' => true,
            'message' => 'Prestasi berhasil diperbarui'
        ]);
    }

    public function confirm_ajax(string $id)
    {
        $prestasi = PrestasiModel::find($id);
        return view('prestasi.confirm_ajax', ['prestasi' => $prestasi]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $prestasi = PrestasiModel::find($id);
            if (!$prestasi) {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
            }

            DetailPrestasiModel::where('prestasi_id', $id)->delete();
            $prestasi->delete();

            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        }

        return redirect('/');
    }

    public function approve_ajax(Request $request, $id)
    {
        $prestasi = PrestasiModel::findOrFail($id);

        if (empty($prestasi->catatan) || $prestasi->status == 'Ditolak') {
            $prestasi->catatan = $request->input('catatan', 'Tidak ada catatan');
        }

        $prestasi->status = 'Disetujui';
        $prestasi->save();

        return response()->json(['success' => 'Prestasi berhasil disetujui']);
    }

    public function reject_ajax(Request $request, $id)
    {
        $prestasi = PrestasiModel::findOrFail($id);
        // Cek jika ada catatan yang diberikan
        $catatan = $request->input('catatan');
        if (!$catatan) {
            return response()->json(['error' => 'Catatan wajib diisi!'], 400); // Jika catatan tidak diisi, kirimkan error
        }

        $prestasi->status = 'Ditolak';
        $prestasi->catatan = $catatan;
        $prestasi->save();

        return response()->json(['success' => 'Prestasi berhasil ditolak']);
    }

    public function search(Request $request)
    {
        $search = $request->q;
        $mahasiswa = MahasiswaModel::where('nama_lengkap', 'LIKE', "%$search%")
            ->select('nim', 'nama_lengkap')
            ->limit(20)
            ->get();

        return response()->json($mahasiswa);
    }

    public function getDetail($id)
    {
        $lomba = LombaModel::with('bidangKeahlian')->find($id);

        if (!$lomba) {
            return response()->json(['status' => false, 'message' => 'Lomba tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'penyelenggara' => $lomba->penyelenggara,
                'tanggal_perolehan' => $lomba->tanggal_selesai,
                'tingkat' => $lomba->tingkat,
                'kategori' => $lomba->bidangKeahlian->keahlian, // atau bidang_keahlian
            ]
        ]);
    }


}