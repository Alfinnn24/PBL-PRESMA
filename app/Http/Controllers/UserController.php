<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $role = UserModel::select('role')
            ->distinct()
            ->get(); // filter role user

        $activeMenu = 'user'; // set menu yang sedang aktif
        return view('admin.user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'role' => $role, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $users = UserModel::select('id', 'username', 'email', 'role');

        // filter data user by role..
        if ($request->role) {
            $users->where('role', $request->role);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('action', function ($user) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Ajax ver
    public function create_ajax()
    {
        $role = UserModel::select('role')->distinct()->get();
        $programStudi = ProgramStudiModel::all(); // ambil semua program studi

        return view('admin.user.create_ajax', [
            'role' => $role,
            'programStudi' => $programStudi
        ]);
    }

    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'role' => 'required',
                'username' => 'required|min:3|max:20|unique:user,username',
                'email' => 'required|email|max:100|unique:users,email',
                'password' => 'required|min:6|max:20',
                'nama_lengkap' => 'required',
            ];

            if ($request->role === 'mahasiswa') {
                $rules = array_merge($rules, [
                    'angkatan' => 'required',
                    'no_telp' => 'required',
                    'alamat' => 'required',
                    'program_studi_id' => 'required',
                ]);
            } elseif ($request->role === 'dosen') {
                $rules = array_merge($rules, [
                    'no_telp_dosen' => 'required',
                    'program_studi_id_dosen' => 'required',
                ]);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors() // pesan error validasi
                ]);
            }

            $user = UserModel::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'mahasiswa') {
                MahasiswaModel::create([
                    'user_id' => $user->id,
                    'nim' => $request->username,
                    'nama_lengkap' => $request->nama_lengkap,
                    'angkatan' => $request->angkatan,
                    'no_telp' => $request->no_telp,
                    'alamat' => $request->alamat,
                    'program_studi_id' => $request->program_studi_id,
                ]);
            } elseif ($request->role === 'dosen') {
                DosenModel::create([
                    'user_id' => $user->id,
                    'nidn' => $request->username,
                    'nama_lengkap' => $request->nama_lengkap,
                    'no_telp' => $request->no_telp_dosen,
                    'program_studi_id' => $request->program_studi_id_dosen,
                ]);
            } elseif ($request->role === 'admin') {
                AdminModel::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $request->nama_lengkap,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        return redirect('/');
    }
    // ajax ver
    public function show_ajax(string $id)
    {
        $user = UserModel::findOrFail($id);

        $detail = null;

        switch ($user->role) {
            case 'mahasiswa':
                $detail = MahasiswaModel::where('user_id', $user->id)->with('programStudi')->first();
                break;

            case 'dosen':
                $detail = DosenModel::where('user_id', $user->id)->with('programStudi')->first();
                break;

            case 'admin':
                $detail = AdminModel::where('user_id', $user->id)->first();
                break;
        }

        return view('admin.user.show_ajax', [
            'user' => $user,
            'detail' => $detail
        ]);
    }

    // Menampilkan edit user ajax
    public function edit_ajax(string $id)
    {
        $user = UserModel::findOrFail($id);
        $role = UserModel::select('role')->distinct()->get();

        $detail = null;

        if ($user->role === 'mahasiswa') {
            $detail = MahasiswaModel::where('user_id', $user->id)->first();
        } elseif ($user->role === 'dosen') {
            $detail = DosenModel::where('user_id', $user->id)->first();
        } elseif ($user->role === 'admin') {
            $detail = AdminModel::where('user_id', $user->id)->first();
        }

        $prodi = ProgramStudiModel::all();

        return view('admin.user.edit_ajax', compact('user', 'role', 'detail', 'prodi'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'role' => 'required',
                'username' => 'required|min:3|max:20|unique:user,username,' . $id . ',id',
                'email' => 'required|email|max:100|unique:users,email,' . $id . ',id',
                'password' => 'nullable|min:6|max:20',
                'nama_lengkap' => 'required',
            ];

            if ($request->role === 'mahasiswa') {
                $rules = array_merge($rules, [
                    'angkatan' => 'required',
                    'no_telp' => 'required',
                    'alamat' => 'required',
                    'program_studi_id' => 'required',
                ]);
            } elseif ($request->role === 'dosen') {
                $rules = array_merge($rules, [
                    'no_telp_dosen' => 'required',
                    'program_studi_id_dosen' => 'required',
                ]);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $user = UserModel::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan'
                ]);
            }

            // Update data user
            $user->username = $request->username;
            $user->email = $request->email;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update data role terkait
            if ($request->role === 'mahasiswa') {
                MahasiswaModel::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nim' => $request->username,
                        'nama_lengkap' => $request->nama_lengkap,
                        'angkatan' => $request->angkatan,
                        'no_telp' => $request->no_telp,
                        'alamat' => $request->alamat,
                        'program_studi_id' => $request->program_studi_id,
                    ]
                );
            } elseif ($request->role === 'dosen') {
                DosenModel::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nidn' => $request->username,
                        'nama_lengkap' => $request->nama_lengkap,
                        'no_telp' => $request->no_telp_dosen,
                        'program_studi_id' => $request->program_studi_id_dosen,
                    ]
                );
            } elseif ($request->role === 'admin') {
                AdminModel::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_lengkap' => $request->nama_lengkap,
                    ]
                );
            }

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil diperbarui'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);

        return view('admin.user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                try {
                    $user->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak bisa dihapus'
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
}
