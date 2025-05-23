<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LombaModel;
use App\Models\BidangKeahlianModel;
use App\Models\PeriodeModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LombaController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Lomba',
            'list' => ['Home', 'lomba']
        ];

        $page = (object) [
            'title' => 'Daftar lomba yang terdaftar di sistem'
        ];
        
        $activeMenu = 'lomba';
        $bidang_keahlian = BidangKeahlianModel::all();
        $periode = PeriodeModel::all();
        
        return view('admin.lomba.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu,
            'bidang_keahlian' => $bidang_keahlian,
            'periode' => $periode
        ]);
    }

    public function list(Request $request)
{

    $lomba = LombaModel::join('bidang_keahlian', 'lomba.bidang_keahlian_id', '=', 'bidang_keahlian.id')
        ->join('periode', 'lomba.periode_id', '=', 'periode.id')
        ->select(
            'lomba.id',
            'lomba.nama',
            'lomba.penyelenggara',
            'lomba.tingkat',
            'bidang_keahlian.keahlian as keahlian',
            'lomba.persyaratan',
            'lomba.jumlah_peserta',
            'lomba.link_registrasi',
            'lomba.tanggal_mulai',
            'lomba.tanggal_selesai',
            'periode.nama as periode_id',
            'lomba.is_verified'
        )
        ->when($request->bidang_keahlian, function ($query) use ($request) {
            $query->where('lomba.bidang_keahlian_id', $request->bidang_keahlian);
        })
        ->when($request->nama, function ($query) use ($request) {
            $query->where('lomba.periode_id', $request->nama);
        }); // <--- titik koma di sini WAJIB

    return DataTables::of($lomba)
        ->addIndexColumn()
        ->filterColumn('keahlian', function($query, $keyword) {
            $query->where('bidang_keahlian.keahlian', 'like', "%{$keyword}%");
        })
        ->filterColumn('periode_nama', function($query, $keyword) {
            $query->where('periode.nama', 'like', "%{$keyword}%");
        })
        ->addColumn('aksi', function ($lomba) {
            $btn  = '<button onclick="modalAction(\''.url('/lomba/' . $lomba->id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/lomba/' . $lomba->id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/lomba/' . $lomba->id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}


    public function create_ajax() 
    {
        $bidang_keahlian = BidangKeahlianModel::select('id', 'keahlian')->distinct()->get();
        $periode = PeriodeModel::select('id', 'nama')->distinct()->get();
        $tingkat_lomba = ['Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional'];
    
        return view('admin.lomba.create_ajax', [
            'bidang_keahlian' => $bidang_keahlian,
            'periode' => $periode,
            'tingkat_lomba' => $tingkat_lomba,
        ]);
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'nama'               => 'required|string|max:100',
            'penyelenggara'      => 'required|string|max:255',
            'tingkat'            => 'required|string|max:50',
            'bidang_keahlian_id' => 'required|exists:bidang_keahlian,id',
            'persyaratan'        => 'nullable|string|max:500',
            'jumlah_peserta'     => 'nullable|integer|min:1',
            'link_registrasi'    => 'nullable|url|max:255',
            'tanggal_mulai'      => 'required|date',
            'tanggal_selesai'    => 'required|date|after:tanggal_mulai',
            'periode_id'         => 'required|exists:periode,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField'=> $validator->errors(),
            ]);
        }

        // Ambil ID user login (admin)
        $adminId = auth()->id(); // atau auth()->user()->id

        // Pengecekan data duplikat
        $exists = LombaModel::where('nama', $request->nama)
            ->where('penyelenggara', $request->penyelenggara)
            ->where('tingkat', $request->tingkat)
            ->where('bidang_keahlian_id', $request->bidang_keahlian_id)
            ->where('persyaratan', $request->persyaratan)
            ->where('jumlah_peserta', $request->jumlah_peserta)
            ->where('link_registrasi', $request->link_registrasi)
            ->where('tanggal_mulai', $request->tanggal_mulai)
            ->where('tanggal_selesai', $request->tanggal_selesai)
            ->where('periode_id', $request->periode_id)
            ->where('created_by', $adminId) // Gunakan adminId di sini
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Data dengan kombinasi data lomba tersebut sudah ada.',
            ]);
        }

        // Siapkan data untuk disimpan
        $data = $request->all();
        $data['created_by'] = $adminId; // Diisi otomatis
        $data['is_verified'] = 'Disetujui'; // Default

        // Simpan
        LombaModel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data lomba berhasil disimpan'
        ]);
    }

    return redirect('/');
}

    public function edit_ajax($id)
    {
    // Ambil data lomba berdasarkan ID
    $lomba = LombaModel::find($id);

    // Memastikan jika data lomba ditemukan
    if (!$lomba) {
        return response()->json([
            'status' => false,
            'message' => 'Lomba tidak ditemukan.',
        ]);
    }

    // Ambil data bidang_keahlian, periode, dan user
    $bidang_keahlian = BidangKeahlianModel::select('id', 'keahlian')->distinct()->get();
    $periode = PeriodeModel::select('id', 'nama')->distinct()->get();
    $tingkat_lomba = ['Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional'];


    // Kirim data ke view
    return view('admin.lomba.edit_ajax', [
        'lomba' => $lomba, 
        'bidang_keahlian' => $bidang_keahlian,
        'periode' => $periode,
        'tingkat_lomba' => $tingkat_lomba
    ]);
}

    public function update_ajax(Request $request, $id)
    { 
        if ($request->ajax() || $request->wantsJson()) { 
            // Cek apakah data ditemukan
            $check = LombaModel::find($id);
            if (!$check) { 
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan: ID ' . $id
                ]);
            }

            // Validasi input
            $rules = [
                'nama'               => 'required|string|max:100',
                'penyelenggara'      => 'required|string|max:255',
                'tingkat'            => 'required|string|max:50',
                'bidang_keahlian_id' => 'required|exists:bidang_keahlian,id',  
                'persyaratan'        => 'nullable|string|max:500',               
                'jumlah_peserta'     => 'nullable|integer|min:1',                
                'link_registrasi'    => 'nullable|url|max:255',                  
                'tanggal_mulai'      => 'required|date',    
                'tanggal_selesai'    => 'required|date|after:tanggal_mulai',    
                'periode_id'         => 'required|exists:periode,id',                                              
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
            $duplicate = LombaModel::where('nama', $request->nama)
                ->where('penyelenggara', $request->penyelenggara)
                ->where('tingkat', $request->tingkat)
                ->where('bidang_keahlian_id', $request->bidang_keahlian_id)
                ->where('persyaratan', $request->persyaratan)
                ->where('jumlah_peserta', $request->jumlah_peserta)
                ->where('link_registrasi', $request->link_registrasi)
                ->where('tanggal_mulai', $request->tanggal_mulai)
                ->where('tanggal_selesai', $request->tanggal_selesai)
                ->where('periode_id', $request->periode_id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                   'status'  => false,
                   'message' => 'Data dengan kombinasi data lomba tersebut sudah ada.'
                ]);
            }
     
            // Proses update
            $check->update($request->only(['nama', 'penyelenggara', 'tingkat', 'bidang_keahlian_id', 'persyaratan', 'jumlah_peserta', 'link_registrasi', 'tanggal_mulai', 'tanggal_selesai', 'periode_id'])); 
            return response()->json([ 
                'status'  => true, 
                'message' => 'Data berhasil diupdate' 
            ]); 
        } 
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $lomba = LombaModel::find($id);
        return view('admin.lomba.confirm_ajax', ['lomba' => $lomba]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $lomba = LombaModel::find($id);
            if ($lomba) {
                try {
                    $lomba->delete();
                return response()->json([
                    'status'=> true,
                    'message'=> 'Data berhasil dihapus'
                ]);
                } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status'=> false,
                    'message'=> 'Data lomba gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
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
        $lomba = LombaModel::with(['creator.dosen', 'creator.admin'])->find($id);
        return view('admin.lomba.show_ajax', ['lomba' =>$lomba]);
    }

    public function approve(Request $request, string $id)
{
    $lomba = LombaModel::find($id);
    if (!$lomba) {
        return response()->json(['status' => 'error', 'message' => 'Lomba tidak ditemukan'], 404);
    }

    $lomba->is_verified = 'Disetujui';
    $lomba->save();

    return response()->json(['status' => 'success', 'message' => 'Lomba disetujui']);
}

public function reject(Request $request, string $id)
{
    $lomba = LombaModel::find($id);
    if (!$lomba) {
        return response()->json(['status' => 'error', 'message' => 'Lomba tidak ditemukan'], 404);
    }

    $lomba->is_verified = 'Ditolak';
    $lomba->save();

    return response()->json(['status' => 'success', 'message' => 'Lomba ditolak']);
}

}