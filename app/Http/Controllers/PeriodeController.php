<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PeriodeController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Periode',
            'list' => ['Home', 'Periode']
        ];

        $page = (object) [
            'title' => 'Daftar Periode yang terdaftar di sistem'
        ];
        
        $nama = PeriodeModel::select('nama')
            ->distinct()
            ->get(); // filter nama periode
        $activeMenu = 'periode';
        
        return view('admin.periode.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu,
            'nama' => $nama
        ]);
    }

    public function list(Request $request)
    {
        $periode = PeriodeModel::select('id', 'nama', 'tahun', 'semester');

        if ($request->nama) {
            $periode->where('nama', $request->nama);
        }

        return DataTables::of($periode)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode) { 
                
                $btn  = '<button onclick="modalAction(\''.url('/periode/' . $periode->id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/periode/' . $periode->id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/periode/' . $periode->id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax() 
    {
        $nama = PeriodeModel::select('nama')->distinct()->get();
        return view('admin.periode.create_ajax')
            ->with('nama', $nama);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'tahun' => 'required|digits:4',
                'semester' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(),
                ]);
            }

            // Pengecekan data duplikat
            $exists = PeriodeModel::where('nama', $request->nama)
                ->where('tahun', $request->tahun)
                ->where('semester', $request->semester)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dengan kombinasi Nama, Tahun, dan Semester tersebut sudah ada.',
                ]);
            }

            PeriodeModel::create($request->all());
            return response()->json([
                'status'=> true,
                'message'=> 'Data periode berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax($id)
    {
        $periode = PeriodeModel::find($id);

        return view('admin.periode.edit_ajax', ['periode' => $periode]);
    }

    public function update_ajax(Request $request, $id)
    { 
        if ($request->ajax() || $request->wantsJson()) { 
            // Cek apakah data ditemukan
            $check = PeriodeModel::find($id);
            if (!$check) { 
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan: ID ' . $id
                ]);
            }

            // Validasi input
            $rules = [ 
                'nama' => 'required|string|max:100',
                'tahun' => 'required|digits:4',
                'semester' => 'required|string|max:100',
            ]; 

            $validator = Validator::make($request->all(), $rules); 
            if ($validator->fails()) { 
                return response()->json([ 
                    'status'   => false,   
                    'message'  => 'Validasi gagal.', 
                    'msgField' => $validator->errors() 
                ]); 
            }

            // Cek duplikasi data kecuali ID yang sedang diedit
            $duplicate = PeriodeModel::where('nama', $request->nama)
                ->where('tahun', $request->tahun)
                ->where('semester', $request->semester)
                ->where('id', '!=', $id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                   'status'  => false,
                   'message' => 'Data dengan kombinasi Nama, Tahun, dan Semester tersebut sudah ada.'
                ]);
            }
     
            // Proses update
            $check->update($request->only(['nama', 'tahun', 'semester'])); 
            return response()->json([ 
                'status'  => true, 
                'message' => 'Data berhasil diupdate' 
            ]); 
        } 
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('admin.periode.confirm_ajax', ['periode' => $periode]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $periode = PeriodeModel::find($id);
            if ($periode) {
                try {
                    $periode->delete();
                return response()->json([
                    'status'=> true,
                    'message'=> 'Data berhasil dihapus'
                ]);
                } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status'=> false,
                    'message'=> 'Data periode gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                ]);
                } 
            } else {
                return response()->json([
                    'status'=> false,
                    'message'=> 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('admin.periode.show_ajax', ['periode' =>$periode]);
    }
}