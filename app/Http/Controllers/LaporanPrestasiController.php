<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrestasiModel;
use App\Models\BidangKeahlianModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class LaporanPrestasiController extends Controller
{
    private function buildQuery(Request $request)
    {
        $bidangKeahlian = $request->input('bidang_keahlian');
        $tingkat = $request->input('tingkat');
        $tahunPrestasi = $request->input('tahun_prestasi');

        $query = PrestasiModel::with([
            'lomba' => function ($q) {
                $q->with('bidangKeahlian');
            },
            'detailPrestasi.mahasiswa'
        ]);

        if ($bidangKeahlian) {
            $query->whereHas('lomba.bidangKeahlian', function ($q) use ($bidangKeahlian) {
                $q->where('keahlian', $bidangKeahlian);
            });
        }

        if ($tingkat) {
            $query->whereHas('lomba', function ($q) use ($tingkat) {
                $q->where('tingkat', $tingkat);
            });
        }

        if ($tahunPrestasi) {
            $query->whereHas('lomba', function ($q) use ($tahunPrestasi) {
                $q->whereYear('tanggal_mulai', $tahunPrestasi);
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $request->validate([
            'bidang_keahlian' => 'nullable|string|max:50',
            'tingkat' => 'nullable|string|max:50',
            'tahun_prestasi' => 'nullable|digits:4|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $bidangKeahlianList = BidangKeahlianModel::orderBy('keahlian')->get();

        $query = $this->buildQuery($request);
        $prestasi = $query->paginate(20)->withQueryString();
        $allData = $this->buildQuery($request)->get();

        // Statistik bidang keahlian dan tahun prestasi untuk grafik
        $statistikBidangKeahlian = $allData->groupBy(fn($item) => optional($item->lomba->bidangKeahlian)->keahlian ?? 'Unknown')->map->count();
        $statistikTahunPrestasi = $allData->groupBy(function ($item) {
            if (!empty($item->lomba->tanggal_mulai)) {
                return Carbon::parse($item->lomba->tanggal_mulai)->format('Y');
            }
            return 'Unknown';
        })->map->count();

        // Statistik gabungan bidang keahlian & tahun (digabung jadi label)
        $statistikGabungan = $allData->groupBy(function ($item) {
            $bidang = optional($item->lomba->bidangKeahlian)->keahlian ?? 'Unknown';
            $tahun = !empty($item->lomba->tanggal_mulai) ? Carbon::parse($item->lomba->tanggal_mulai)->format('Y') : 'Unknown';
            return $bidang . ' - ' . $tahun;
        })->map->count();

        $breadcrumb = (object) [
            'title' => 'Laporan Prestasi Mahasiswa',
            'list' => ['Home', 'Laporan Prestasi']
        ];

        return view('admin.laporan.index', [
            'prestasi' => $prestasi,
            'statistikBidangKeahlian' => $statistikBidangKeahlian,
            'statistikTahunPrestasi' => $statistikTahunPrestasi,
            'statistikGabungan' => $statistikGabungan,
            'bidangKeahlianList' => $bidangKeahlianList,
            'activeMenu' => 'laporanprestasi',
            'breadcrumb' => $breadcrumb,
        ]);
    }


    public function list(Request $request)
    {
        $query = $this->buildQuery($request);

        return DataTables::eloquent($query)
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value'] ?? null) {
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('detailPrestasi.mahasiswa', function ($q2) use ($search) {
                            $q2->where('nama_lengkap', 'like', "%{$search}%");
                        })
                            ->orWhereHas('lomba', function ($q3) use ($search) {
                                $q3->where('nama', 'like', "%{$search}%")
                                    ->orWhere('tingkat', 'like', "%{$search}%");
                            })
                            ->orWhereHas('lomba.bidangKeahlian', function ($q4) use ($search) {
                                $q4->where('keahlian', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->addColumn('mahasiswa', fn($item) => optional($item->detailPrestasi->first()->mahasiswa)->nama_lengkap ?? '-')
            ->addColumn('lomba', fn($item) => $item->lomba->nama ?? '-')
            ->addColumn('bidang_keahlian', fn($item) => optional($item->lomba->bidangKeahlian)->keahlian ?? '-')
            ->addColumn('tingkat', fn($item) => $item->lomba->tingkat ?? '-')
            ->addColumn('tahun_prestasi', function ($item) {
                return !empty($item->lomba->tanggal_mulai) ? Carbon::parse($item->lomba->tanggal_mulai)->format('Y') : '-';
            })
            ->rawColumns(['mahasiswa', 'lomba', 'bidang_keahlian', 'tingkat', 'tahun_prestasi'])
            ->make(true);
    }

    public function exportExcel(Request $request)
    {
        $query = $this->buildQuery($request);
        $data = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Mahasiswa');
        $sheet->setCellValue('B1', 'Lomba');
        $sheet->setCellValue('C1', 'Bidang Keahlian');
        $sheet->setCellValue('D1', 'Tingkat');
        $sheet->setCellValue('E1', 'Tahun Prestasi');

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue("A$row", optional($item->detailPrestasi->first()->mahasiswa)->nama_lengkap ?? '-');
            $sheet->setCellValue("B$row", $item->lomba->nama ?? '-');
            $sheet->setCellValue("C$row", optional($item->lomba->bidangKeahlian)->keahlian ?? '-');
            $sheet->setCellValue("D$row", $item->lomba->tingkat ?? '-');
            $sheet->setCellValue("E$row", !empty($item->lomba->tanggal_mulai) ? Carbon::parse($item->lomba->tanggal_mulai)->format('Y') : '-');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan_prestasi_' . now()->format('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildQuery($request);
        $data = $query->get();

        $pdf = PDF::loadView('admin.laporan.pdf', compact('data'));
        $filename = 'laporan_prestasi_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }


}
