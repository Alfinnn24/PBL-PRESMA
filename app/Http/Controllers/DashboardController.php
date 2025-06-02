<?php

namespace App\Http\Controllers;

use App\Models\PrestasiModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;
use App\Models\BidangKeahlianModel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $breadcrumb = (object) [
            'title' => 'Selamat Datang, ' . ($user->fullName ?? ''),
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Jumlah Prestasi
        $jumlahPrestasi = PrestasiModel::count();

        // Bidang dengan prestasi terbanyak
        $bidangTerbanyak = PrestasiModel::with('lomba.bidangKeahlian')
            ->get()
            ->groupBy(fn($p) => optional($p->lomba->bidangKeahlian)->keahlian)
            ->map(fn($items) => $items->count())
            ->sortDesc()
            ->take(1)
            ->keys()
            ->first();

        // Efektivitas sistem rekomendasi (Disetujui / Total)
        $totalRekomendasi = RekomendasiLombaModel::count();
        $rekomendasiDisetujui = RekomendasiLombaModel::where('status', 'Disetujui')->count();
        $efektivitas = $totalRekomendasi > 0 ? round(($rekomendasiDisetujui / $totalRekomendasi) * 100, 2) : 0;

        // 3 Prestasi Terbaru
        $prestasiTerbaru = PrestasiModel::with('lomba')->latest()->take(3)->get();

        // 3 Lomba Terbaru
        $lombaTerbaru = LombaModel::latest()->take(3)->get();

        // Grafik batang: Jumlah prestasi per periode
        $prestasiPerPeriode = PrestasiModel::with('lomba.periode')
            ->get()
            ->groupBy(fn($p) => optional($p->lomba->periode)->nama ?? 'Tanpa Periode')
            ->map->count();

        // Grafik lingkaran: Jumlah prestasi berdasarkan bidang keahlian
        $prestasiPerBidang = PrestasiModel::with('lomba.bidangKeahlian')
            ->get()
            ->groupBy(fn($p) => optional($p->lomba->bidangKeahlian)->keahlian ?? 'Tidak diketahui')
            ->map->count();

        // Ranking mahasiswa dengan prestasi terbanyak
        $rankingMahasiswa = DB::table('detail_prestasi')
            ->join('mahasiswa', 'detail_prestasi.mahasiswa_nim', '=', 'mahasiswa.nim')
            ->select('detail_prestasi.mahasiswa_nim', 'mahasiswa.nama_lengkap', DB::raw('COUNT(*) as total'))
            ->groupBy('detail_prestasi.mahasiswa_nim', 'mahasiswa.nama_lengkap')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'jumlahPrestasi',
            'bidangTerbanyak',
            'efektivitas',
            'prestasiTerbaru',
            'lombaTerbaru',
            'prestasiPerPeriode',
            'prestasiPerBidang',
            'rankingMahasiswa',
            'breadcrumb',
            'activeMenu'
        ));
    }
}
