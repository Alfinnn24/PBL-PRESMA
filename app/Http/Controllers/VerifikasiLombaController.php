<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LombaModel;
use App\Models\BidangKeahlianModel;
use App\Models\PeriodeModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class VerifikasiLombaController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Verifikasi Lomba',
            'list' => ['Home', 'Verifikasi Lomba']
        ];

        $page = (object) [
            'title' => 'Daftar Lomba Menunggu Verifikasi'
        ];
        
        $activeMenu = 'verifikasi_lomba';
        $bidang_keahlian = BidangKeahlianModel::all();
        $periode = PeriodeModel::all()->map(function($item) {
            $item->display_name = $item->nama . ' ' . $item->semester;
            return $item;
        });   
        
        return view('admin.verifikasi_lomba.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu,
            'bidang_keahlian' => $bidang_keahlian,
            'periode' => $periode
        ]);
    }

    public function list(Request $request) {
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
        ->where('lomba.is_verified', 'Pending');

    return DataTables::of($lomba)
        ->addIndexColumn()
        ->addColumn('aksi', function ($lomba) {
            return '
            <button onclick="modalAction(\''.url('/verifikasi_lomba/'.$lomba->id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>
            <button type="button" onclick="ubahStatus('.$lomba->id.', \'approve\')" class="btn btn-success btn-sm">Setujui</button>
            <button type="button" onclick="ubahStatus('.$lomba->id.', \'reject\')" class="btn btn-danger btn-sm">Tolak</button>
            ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function show_ajax($id)
    {
        $lomba = LombaModel::with(['creator.dosen', 'creator.admin', 'creator.mahasiswa'])->find($id);

        if (!$lomba) {
            return view('admin.verifikasi_lomba.show_ajax', ['error' => 'Data lomba tidak ditemukan']);
        }

        return view('admin.verifikasi_lomba.show_ajax', compact('lomba'));
    }

    public function approve($id)
    {
        $lomba = LombaModel::findOrFail($id);
        $lomba->update(['is_verified' => 'Disetujui']);

        return redirect()->back()->with('success', 'Lomba berhasil disetujui.');
    }

    public function reject($id)
    {
        $lomba = LombaModel::findOrFail($id);
        $lomba->update(['is_verified' => 'Ditolak']);

        return redirect()->back()->with('success', 'Lomba berhasil ditolak.');
    }

}