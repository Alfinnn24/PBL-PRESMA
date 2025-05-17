<?php

namespace App\Http\Controllers;

use App\Models\LombaModel;
use App\Models\PrestasiModel;
use App\Models\MahasiswaModel;
use App\Models\DetailPrestasiModel;
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

        if ($user->role === 'admin') {
            return view('admin.prestasi.index', compact('breadcrumb', 'page', 'activeMenu'));
        } else {
            return view('prestasi.index', compact('breadcrumb', 'page', 'activeMenu'));
        }
    }

    public function list(Request $request)
    {
        $user = auth()->user(); // mendapatkan user yang login

        if ($user->role === 'admin') {
            // Admin melihat semua data
            $data = PrestasiModel::with('detailPrestasi.mahasiswa')
                ->get()
                ->filter(function ($item) {
                    return $item->detailPrestasi->count() > 0;
                });
        } else {
            // Mahasiswa hanya melihat data dirinya
            $mahasiswa = $user->mahasiswa; // asumsi relasi: User -> mahasiswa
            $data = PrestasiModel::with(['detailPrestasi.mahasiswa'])
                ->whereHas('detailPrestasi.mahasiswa', function ($query) use ($mahasiswa) {
                    $query->where('nim', $mahasiswa->nim);
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
                    // tombol Setujui dan Tolak hanya untuk admin
                    $btn .= '<button onclick="ubahStatus(' . $row->id . ', \'approve\')" class="btn btn-success btn-sm">Setujui</button> ';
                    $btn .= '<button onclick="ubahStatus(' . $row->id . ', \'reject\')" class="btn btn-danger btn-sm">Tolak</button>';
                } else {
                    // selain admin (mahasiswa/dosen) bisa edit dan delete
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
        $prestasi = PrestasiModel::with(['lomba', 'detailPrestasi.mahasiswa', 'creator.mahasiswa', 'creator.dosen', 'creator.admin'])->findOrFail($id);
        return view('admin.prestasi.show_ajax', compact('prestasi'));
    }


    public function edit_ajax($id)
    {
        $prestasi = PrestasiModel::with('detailPrestasi')->findOrFail($id);
        $mahasiswa = MahasiswaModel::all();
        return view('admin.prestasi.edit_ajax', compact('prestasi', 'mahasiswa'));
    }

    public function update_ajax(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_prestasi' => 'required',
            'tingkat' => 'required',
            'tahun' => 'required|integer',
            'mahasiswa_id' => 'required|array',
            'mahasiswa_id.*' => 'exists:mahasiswa,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $prestasi = PrestasiModel::findOrFail($id);
        $prestasi->update([
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat' => $request->tingkat,
            'tahun' => $request->tahun
        ]);

        // Hapus dan tambah ulang relasi mahasiswa
        DetailPrestasiModel::where('prestasi_id', $prestasi->id)->delete();
        foreach ($request->mahasiswa_id as $mhsId) {
            DetailPrestasiModel::create([
                'prestasi_id' => $prestasi->id,
                'mahasiswa_id' => $mhsId
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Prestasi berhasil diperbarui'
        ]);
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


}