<?php

namespace App\Http\Controllers;

use App\Models\PrestasiModel;
use App\Models\MahasiswaModel;
use App\Models\DetailPrestasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PrestasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Prestasi',
            'list' => ['Home', 'Prestasi']
        ];

        $page = (object) [
            'title' => 'Daftar Prestasi Mahasiswa'
        ];

        $activeMenu = 'verifprestasi';

        return view('admin.prestasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $user = auth()->user(); // mendapatkan user yang login
        $data = PrestasiModel::with('detailPrestasi.mahasiswa')
            ->get()
            ->filter(function ($item) {
                return $item->detailPrestasi->count() > 0;
            });

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
        $mahasiswa = MahasiswaModel::all();
        return view('admin.prestasi.create_ajax', compact('mahasiswa'));
    }

    public function store_ajax(Request $request)
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

        $prestasi = PrestasiModel::create([
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat' => $request->tingkat,
            'tahun' => $request->tahun
        ]);

        foreach ($request->mahasiswa_id as $id) {
            DetailPrestasiModel::create([
                'prestasi_id' => $prestasi->id,
                'mahasiswa_id' => $id
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Prestasi berhasil ditambahkan'
        ]);
    }

    public function show_ajax($id)
    {
        $prestasi = PrestasiModel::with(['lomba', 'detailPrestasi.mahasiswa'])->findOrFail($id);
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

    public function approve_ajax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);
        $prestasi->status = 'Disetujui';
        $prestasi->save();

        return response()->json(['success' => 'Prestasi berhasil disetujui']);
    }

    public function reject_ajax($id)
    {
        $prestasi = PrestasiModel::findOrFail($id);
        $prestasi->status = 'Ditolak';
        $prestasi->save();

        return response()->json(['success' => 'Prestasi berhasil ditolak']);
    }

}