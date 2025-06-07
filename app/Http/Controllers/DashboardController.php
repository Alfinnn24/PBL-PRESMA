<?php

namespace App\Http\Controllers;

use App\Models\DetailPrestasiModel;
use App\Models\MahasiswaModel;
use App\Models\PendaftaranLombaModel;
use App\Models\PrestasiModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;
use App\Models\BidangKeahlianModel;
use App\Models\DosenModel;
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

    public function indexDosen()
    {
        $user = auth()->user();
        $breadcrumb = (object) [
            'title' => 'Selamat Datang, ' . ($user->fullName ?? ''),
            'list' => ['Home', 'Dashboard Dosen']
        ];

        $activeMenu = 'dashboard';

        // Ambil ID dosen dari user login
        $dosen = DosenModel::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Data dosen tidak ditemukan.');
        }

        // 1. Jumlah mahasiswa bimbingan
        $jumlahMahasiswaBimbingan = DB::table('dosen_pembimbing')
            ->where('dosen_id', $dosen->id)
            ->count();

        // 2. Jumlah rekomendasi lomba untuk dosen ini
        $jumlahRekomendasiDosen = RekomendasiLombaModel::where('dosen_pembimbing_id', $dosen->id)
            ->groupBy('lomba_id')
            ->select('lomba_id')
            ->get()
            ->count();

        // 3. Lomba yang dibuat oleh dosen ini

        $lombaDosen = LombaModel::where('created_by', $user->id);
        $totalLomba = $lombaDosen->count();
        $lombaDisetujui = $lombaDosen->where('is_verified', 'Disetujui')->count();

        // Hitung persentase disetujui
        $persentaseDisetujui = $totalLomba > 0 ? round(($lombaDisetujui / $totalLomba) * 100, 2) : 0;

        return view('dashboard.dosen', compact(
            'breadcrumb',
            'activeMenu',
            'jumlahMahasiswaBimbingan',
            'jumlahRekomendasiDosen',
            'totalLomba',
            'lombaDisetujui',
            'persentaseDisetujui'
        ));
    }

    public function indexMahasiswa()
    {
        $user = auth()->user();

        $breadcrumb = (object) [
            'title' => 'Selamat Datang, ' . ($user->fullName ?? ''),
            'list' => ['Home', 'Dashboard Mahasiswa']
        ];

        $activeMenu = 'dashboard';

        // Ambil mahasiswa berdasarkan user yang login
        $mahasiswa = MahasiswaModel::where('user_id', $user->id)->first();

        // Hitung jumlah prestasi mahasiswa
        $jumlahPrestasi = DetailPrestasiModel::where('mahasiswa_nim', $mahasiswa->nim)->count();

        // Hitung jumlah lomba disetujui dari pendaftaran (status = Disetujui)
        $jumlahLombaDisetujui = LombaModel::where('created_by', $user->id)
            ->where('is_verified', 'Disetujui')
            ->count();

        // Hitung semua pendaftaran lomba mahasiswa ini
        $jumlahPendaftaran = PendaftaranLombaModel::where('mahasiswa_nim', $mahasiswa->nim)->count();

        // Ambil 3 rekomendasi lomba dengan skor tertinggi untuk mahasiswa
        $rekomendasiTop = RekomendasiLombaModel::with('lomba')
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->orderByDesc('skor')
            ->take(3)
            ->get();

        // Ambil 3 pendaftaran lomba terbaru
        $pendaftaranTerbaru = PendaftaranLombaModel::with('lomba')
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard.mahasiswa', compact(
            'breadcrumb',
            'activeMenu',
            'jumlahPrestasi',
            'jumlahLombaDisetujui',
            'jumlahPendaftaran',
            'rekomendasiTop',
            'pendaftaranTerbaru'
        ));
    }


}
