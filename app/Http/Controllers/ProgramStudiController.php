<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramStudiModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Program Studi',
            'list' => ['Home', 'Program Studi']
        ];

        $page = (object) [
            'title' => 'Daftar program studi yang terdaftar di sistem'
        ];

        $nama = ProgramStudiModel::select('nama_prodi')->distinct()->get();
        $activeMenu = 'program_studi';

        return view('admin.program_studi.index', compact('breadcrumb', 'page', 'activeMenu', 'nama'));
    }

   public function list(Request $request)
    {
        $programStudi = ProgramStudiModel::select('id', 'nama_prodi', 'kode_prodi', 'jenjang');

        if ($request->nama_prodi) {
            $programStudi->where('nama_prodi', $request->nama_prodi);
        }

        return DataTables::of($programStudi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($programStudi) {
                $btn  = '<button onclick="modalAction(\'' . url('/program_studi/' . $programStudi->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/program_studi/' . $programStudi->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/program_studi/' . $programStudi->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('admin.program_studi.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_prodi' => 'required|max:100',
            'kode_prodi' => 'required|max:10',
            'jenjang' => 'required|in:D3,D4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $exists = ProgramStudiModel::where('kode_prodi', $request->kode_prodi)->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Kode prodi sudah digunakan.'
            ]);
        }

        ProgramStudiModel::create($request->only('nama_prodi', 'kode_prodi', 'jenjang'));

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

    public function edit_ajax($id)
    {
        $programStudi = ProgramStudiModel::findOrFail($id);
        return view('admin.program_studi.edit_ajax', compact('programStudi'));
    }

    public function update_ajax(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_prodi' => 'required|max:100',
            'kode_prodi' => 'required|max:10',
            'jenjang' => 'required|in:D3,D4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $programStudi = ProgramStudiModel::findOrFail($id);

        $duplicate = ProgramStudiModel::where('kode_prodi', $request->kode_prodi)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'status' => false,
                'message' => 'Kode prodi sudah digunakan oleh data lain.'
            ]);
        }

        $programStudi->update($request->only('nama_prodi', 'kode_prodi', 'jenjang'));

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    public function confirm_ajax($id)
    {
        $programStudi = ProgramStudiModel::findOrFail($id);
        return view('admin.program_studi.confirm_ajax', compact('programStudi'));
    }

    public function delete_ajax($id)
    {
        $programStudi = ProgramStudiModel::find($id);

        if (!$programStudi) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        try {
            $programStudi->delete();
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

    public function show_ajax($id)
    {
        $programStudi = ProgramStudiModel::findOrFail($id);
        return view('admin.program_studi.show_ajax', compact('programStudi'));
    }
}
