<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Yajra\DataTables\Facades\DataTables;

class DosenBimbinganController extends Controller
{
    /**
     * Tampilkan halaman daftar mahasiswa bimbingan (blade).
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Mahasiswa Bimbingan',
            'list' => ['Home', 'Mahasiswa Bimbingan']
        ];

        $page = (object) [
            'title' => 'Daftar Mahasiswa Bimbingan',
        ];

        return view('bimbingan.index', compact('breadcrumb', 'page'))
            ->with('activeMenu', 'datamhs');
    }

    /**
     * Endpoint JSON untuk DataTables daftar mahasiswa bimbingan.
     */
    public function list(Request $request)
    {
        $dosen = auth()->user()->dosen;

        if (!$dosen) {
            abort(403, 'Anda bukan dosen pembimbing');
        }

        $query = MahasiswaModel::whereHas('dosenPembimbing', function ($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })->withCount('prestasi');

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $url = url('/dosen/bimbingan/' . $row->nim);
                return '<a href="' . $url . '" class="btn btn-sm btn-primary">Detail Prestasi</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Tampilkan detail prestasi mahasiswa bimbingan.
     */
   public function show(Request $request, $nim)
{
    $dosen = auth()->user()->dosen;

    if (!$dosen) {
        abort(403, 'Anda bukan dosen pembimbing');
    }

    // Pastikan mahasiswa dibimbing oleh dosen yang login
    $mahasiswa = MahasiswaModel::where('nim', $nim)
        ->whereHas('dosenPembimbing', function ($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id);
        })
        ->firstOrFail();

    // Query detail prestasi beserta data lomba terkait
    $prestasiQuery = $mahasiswa->prestasi()->with('prestasi.lomba');

    // Filter kategori berdasarkan kolom lomba.kategori di tabel lomba
    if ($request->filled('kategori')) {
        $kategori = $request->kategori;
        $prestasiQuery->whereHas('prestasi.lomba', function ($q) use ($kategori) {
            $q->where('kategori', $kategori);
        });
    }

    // Filter tahun berdasarkan created_at di tabel prestasi
    if ($request->filled('tahun')) {
        $tahun = $request->tahun;
        $prestasiQuery->whereHas('prestasi', function ($q) use ($tahun) {
            $q->whereYear('created_at', $tahun);
        });
    }

    $prestasis = $prestasiQuery->paginate(10)->withQueryString();

    // Ambil daftar kategori unik dari lomba mahasiswa ini
    $kategoriList = $mahasiswa->prestasi()
        ->join('prestasi', 'detail_prestasi.prestasi_id', '=', 'prestasi.id')
        ->join('lomba', 'prestasi.lomba_id', '=', 'lomba.id')
        ->select('lomba.tingkat')
        ->distinct()
        ->pluck('tingkat');

    // Ambil daftar tahun unik dari prestasi mahasiswa ini
    $tahunList = $mahasiswa->prestasi()
        ->join('prestasi', 'detail_prestasi.prestasi_id', '=', 'prestasi.id')
        ->selectRaw('YEAR(prestasi.created_at) as tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');

   $breadcrumb = (object) [
            'title' => 'Data Mahasiswa Bimbingan',
            'list' => ['Home', 'Mahasiswa Bimbingan']
        ];

        $page = (object) [
            'title' => 'Daftar Mahasiswa Bimbingan',
        ];

    return view('bimbingan.show', compact('mahasiswa', 'prestasis', 'kategoriList', 'tahunList', 'breadcrumb', 'page'))
        ->with('activeMenu', 'datamhs');
}

}
