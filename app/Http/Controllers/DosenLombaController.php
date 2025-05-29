<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LombaModel;
use App\Models\BidangKeahlianModel;
use App\Models\PeriodeModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DosenLombaController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Lomba Tersedia',
            'list' => ['Home', 'lomba']
        ];

        $page = (object) [
            'title' => 'Daftar lomba yang telah disetujui'
        ];
        
        $activeMenu = 'lomba_dosen';
        $bidang_keahlian = BidangKeahlianModel::all();
        $periode = PeriodeModel::all()->map(function($item) {
            $item->display_name = $item->nama . ' ' . $item->semester;
            return $item;
        });        
        
        return view('dosen.lomba.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu,
            'bidang_keahlian' => $bidang_keahlian,
            'periode' => $periode
        ]);
    }

    public function list(Request $request)
{
    $dosenId = auth()->id(); // Ambil ID user login

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
            \DB::raw("CONCAT(periode.nama, ' ', periode.semester) as periode_display_name"),
            'lomba.is_verified'
        )
        ->whereIn('lomba.is_verified', ['Disetujui', 'Pending', 'Ditolak']) // tampilkan yang disetujui, pending, ditolak
        ->where('lomba.created_by', $dosenId)     // Filter hanya yang dibuat oleh dosen login
        ->when($request->bidang_keahlian, function ($query) use ($request) {
            $query->where('lomba.bidang_keahlian_id', $request->bidang_keahlian);
        })
        ->when($request->nama, function ($query) use ($request) {
            $query->where('lomba.periode_id', $request->nama);
        });

    return DataTables::of($lomba)
        ->addIndexColumn()
        ->filterColumn('keahlian', function($query, $keyword) {
            $query->where('bidang_keahlian.keahlian', 'like', "%{$keyword}%");
        })
        ->filterColumn('periode_nama', function($query, $keyword) {
            $query->where('periode.nama', 'like', "%{$keyword}%");
        })
        ->addColumn('aksi', function ($lomba) {
            return '<button onclick="modalAction(\''.url('/dosen/lomba/' . $lomba->id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>';
        })
        ->rawColumns(['aksi'])
        ->make(true);
}

public function create_ajax() 
{
    $bidang_keahlian = BidangKeahlianModel::select('id', 'keahlian')->distinct()->get();
    $periode = PeriodeModel::select('id', 'nama', 'semester')->distinct()->get()->map(function($item) {
        $item->display_name = $item->nama . ' ' . $item->semester;
        return $item;
    });
    $tingkat_lomba = ['Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional'];

    return view('dosen.lomba.create_ajax', [
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

        // Ambil ID user login (dosen)
        $dosenId = auth()->id(); // atau auth()->user()->id

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
            ->where('created_by', $dosenId) // Gunakan dosenId di sini
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Data dengan kombinasi data lomba tersebut sudah ada.',
            ]);
        }

        // Siapkan data untuk disimpan
        $data = $request->all();
        $data['created_by'] = $dosenId; // Diisi otomatis
        $data['is_verified'] = 'Pending'; // Default

        // Simpan
        LombaModel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data lomba berhasil disimpan'
        ]);
    }

    return redirect()->route('dosen.lomba.index')->with('success', 'Lomba berhasil diajukan, menunggu persetujuan admin.');
}

public function show_ajax(string $id)
{
    $lomba = LombaModel::with(['creator.dosen', 'creator.admin'])->find($id);

    if (!$lomba || !in_array($lomba->is_verified, ['Disetujui', 'Pending', 'Ditolak'])) {
        return response()->json([
            'status' => false,
            'message' => 'Lomba tidak ditemukan atau statusnya tidak valid.'
        ]);
    }    

    return view('dosen.lomba.show_ajax', ['lomba' => $lomba]);
}

}