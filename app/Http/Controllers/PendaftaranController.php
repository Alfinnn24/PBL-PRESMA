<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranLombaModel;
use App\Models\MahasiswaModel;
use App\Models\LombaModel;
use App\Models\RekomendasiLombaModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pendaftaran Lomba',
            'list' => ['Home', 'Pendaftaran Lomba']
        ];

        $page = (object) [
            'title' => 'Daftar pendaftaran lomba oleh mahasiswa'
        ];

        $activeMenu = 'pendaftaranLomba';
        $mahasiswa = MahasiswaModel::select('nim', 'nama_lengkap')->get();
        $lomba = LombaModel::select('id', 'nama')->get();

        return view('pendaftaran.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'mahasiswa' => $mahasiswa,
            'lomba' => $lomba
        ]);
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Hanya mahasiswa yang dapat mengakses data ini.');
        }

        // Filter opsional
        $status = $request->status;
        $lombaId = $request->lomba_id;

        $data = PendaftaranLombaModel::with(['lomba'])
            ->where('mahasiswa_nim', $mahasiswa->nim)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($lombaId, function ($query) use ($lombaId) {
                $query->where('lomba_id', $lombaId);
            });

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', function ($row) {
                return $row->lomba->nama ?? '-';
            })
            ->addColumn('tanggal_daftar', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y') : '-';
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->status);
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/pendaftaran/' . $row->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pendaftaran/' . $row->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = MahasiswaModel::select('nim', 'nama_lengkap')->get();
        $lomba = LombaModel::select('id', 'nama')->get();
        $status = ['Terdaftar', 'Selesai', 'Dibatalkan'];
        $lombaTerpilih = $request->query('lomba_id');

        return view('pendaftaran.create_ajax', [
            'mahasiswa' => $mahasiswa,
            'lomba' => $lomba,
            'status' => $status,
            'lombaTerpilih' => $lombaTerpilih,
            'user' => $user
        ]);
    }


    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_nim' => 'required|exists:mahasiswa,nim',
                'lomba_id' => 'required|exists:lomba,id',
                'hasil' => 'nullable|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Cek duplikasi pendaftaran
            $exists = PendaftaranLombaModel::where('mahasiswa_nim', $request->mahasiswa_nim)
                ->where('lomba_id', $request->lomba_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mahasiswa sudah terdaftar pada lomba ini.',
                ]);
            }

            // Simpan data pendaftaran dengan status default 'Terdaftar'
            PendaftaranLombaModel::create([
                'mahasiswa_nim' => $request->mahasiswa_nim,
                'lomba_id' => $request->lomba_id,
                'status' => 'Terdaftar',
                'hasil' => $request->hasil ?? '-',
            ]);

            // Jalankan algoritma approve rekomendasi jika ada
            $rekomendasi = RekomendasiLombaModel::where('mahasiswa_nim', $request->mahasiswa_nim)
                ->where('lomba_id', $request->lomba_id)
                ->first();

            if ($rekomendasi) {
                $rekomendasi->status = 'Disetujui';
                $rekomendasi->save();

                if ($rekomendasi->status_dosen === 'Disetujui') {
                    $exists = \DB::table('dosen_pembimbing')
                        ->where('dosen_id', $rekomendasi->dosen_id)
                        ->where('mahasiswa_nim', $rekomendasi->mahasiswa_nim)
                        ->exists();

                    if (!$exists) {
                        \DB::table('dosen_pembimbing')->insert([
                            'dosen_id' => $rekomendasi->dosen_pembimbing_id,
                            'mahasiswa_nim' => $rekomendasi->mahasiswa_nim,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Data pendaftaran berhasil disimpan',
            ]);
        }

        // Redirect ke halaman pendaftaran jika request bukan AJAX
        return redirect('/');
    }



    public function edit_ajax($id)
    {
        $pendaftaran = PendaftaranLombaModel::find($id);

        if (!$pendaftaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data pendaftaran tidak ditemukan.',
            ]);
        }

        $mahasiswa = MahasiswaModel::select('nim', 'nama_lengkap')->get();
        $lomba = LombaModel::select('id', 'nama')->get();
        $status = ['Terdaftar', 'Selesai', 'Dibatalkan'];

        return view('pendaftaran.edit_ajax', [
            'pendaftaran' => $pendaftaran,
            'mahasiswa' => $mahasiswa,
            'lomba' => $lomba,
            'status' => $status
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pendaftaran = PendaftaranLombaModel::find($id);

            if (!$pendaftaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan: ID ' . $id
                ]);
            }

            $rules = [
                'status' => 'required|in:Terdaftar,Selesai,Dibatalkan',
                'hasil' => 'nullable|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $pendaftaran->update($request->only(['status', 'hasil']));

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate.'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $pendaftaran = PendaftaranLombaModel::find($id);
        return view('admin.pendaftaran_lomba.confirm_ajax', ['pendaftaran' => $pendaftaran]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pendaftaran = PendaftaranLombaModel::find($id);
            if ($pendaftaran) {
                try {
                    $pendaftaran->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus karena masih terdapat relasi dengan data lain'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $pendaftaran = PendaftaranLombaModel::with([
            'mahasiswa',
            'lomba.bidangKeahlian',
            'lomba.creator',
            'lomba.periode'
        ])->find($id);

        return view('pendaftaran.show_ajax', compact('pendaftaran'));
    }

    public function getDetail($id)
    {
        $lomba = LombaModel::with('bidangKeahlian')->find($id);

        if (!$lomba) {
            return response()->json(['status' => false, 'message' => 'Lomba tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'penyelenggara' => $lomba->penyelenggara,
                'tanggal_pelaksanaan' => $lomba->tanggal_selesai,
                'tingkat' => $lomba->tingkat,
                'kategori' => $lomba->bidangKeahlian->keahlian, // atau bidang_keahlian
            ]
        ]);
    }
}
