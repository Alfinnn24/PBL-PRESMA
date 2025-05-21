<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;

class ProfileController extends Controller
{
    // helper fungsi buat get detail model
    private function getDetailModel($user)
    {
        switch ($user->role) {
            case 'mahasiswa':
                return MahasiswaModel::firstOrNew(['user_id' => $user->id]);
            case 'dosen':
                return DosenModel::firstOrNew(['user_id' => $user->id]);
            case 'admin':
                return AdminModel::firstOrNew(['user_id' => $user->id]);
        }
    }
    // Show the user profile based on authenticated user
    public function index()
    {
        $user = UserModel::findOrFail(Auth::id());
        $detail = $this->getDetailModel($user);

        $breadcrumb = (object) [
            'title' => 'Profil Pengguna',
            'list' => ['Home', 'Profil']
        ];

        $page = (object) [
            'title' => 'Informasi Profil Pengguna'
        ];

        $activeMenu = 'profile';

        return view('profile.index', compact('user', 'detail', 'breadcrumb', 'page', 'activeMenu'));
    }

    // Show the form for editing the user profile.
    public function edit()
    {
        $user = UserModel::findOrFail(Auth::id());
        $detail = $this->getDetailModel($user);
        $prodi = ProgramStudiModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Profil',
            'list' => ['Home', 'Profil', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Informasi Profil'
        ];

        $activeMenu = 'profile';

        return view('profile.edit', compact('user', 'detail', 'prodi', 'breadcrumb', 'page', 'activeMenu'));
    }

    // Update the user's profile.
    public function update(Request $request)
    {
        $user   = UserModel::findOrFail(Auth::id());
        $detail = $this->getDetailModel($user);

        // Validation
        $rules = [
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'required|email|max:100|unique:users,email,' . $user->id,
        ];

        if ($user->role === 'mahasiswa') {
            $rules = array_merge($rules, [
                'angkatan' => [
                    'required',
                    'digits:4', // Validasi 4 digit angka
                    'numeric',
                    'min:2000',
                    'max:' . date('Y')
                ],
                'no_telp' => [
                    'required',
                    'numeric',
                    'digits_between:10,15'
                ],
                'alamat'            => ['required'],
                'program_studi_id'  => ['required'],
            ]);
        } elseif ($user->role === 'dosen') {
            $rules = array_merge($rules, [
                'no_telp'           => 'required',
                'program_studi_id'  => 'required',
            ]);
        }

        if ($request->filled('password')) {
            $rules['password'] = 'min:6|max:20|confirmed';
        }

        if ($request->hasFile('foto_profile')) {
            $rules['foto_profile'] = 'image|mimes:jpg,jpeg,png|max:2048';
        }

        // $request->validate($rules);
        $validator = Validator::make($request->all(), $rules, [
            // profile
            'foto_profile.image' => 'File harus berupa gambar.',
            'foto_profile.mimes' => 'Format file harus JPG, JPEG, atau PNG.',
            'foto_profile.max' => 'Ukuran foto profil tidak boleh lebih dari 2MB.',
            // password
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
            // angkatan
            'angkatan.max' => 'Tahun angkatan tidak boleh lebih dari tahun saat ini.',
            // no-telp
            'no_telp.digits_between' => 'Nomor telepon harus antara 10 sampai 15 digit.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Terjadi kesalahan validasi.'
            ], 422);
        }

        // Proses update user dan detail
        try {
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Siapkan data untuk detail (mahasiswa/dosen/admin)
            $dataDetail = [
                'nama_lengkap'       => $request->nama_lengkap,
            ];

            // Role-specific fields
            if ($user->role === 'mahasiswa') {
                $dataDetail = array_merge($dataDetail, [
                    'angkatan'         => $request->angkatan,
                    'no_telp'          => $request->no_telp,
                    'alamat'           => $request->alamat,
                    'program_studi_id' => $request->program_studi_id,
                ]);
            } elseif ($user->role === 'dosen') {
                $dataDetail = array_merge($dataDetail, [
                    'no_telp'          => $request->no_telp,
                    'program_studi_id' => $request->program_studi_id,
                ]);
            }

            // Handle upload foto_profile
            if ($request->hasFile('foto_profile')) {
                // Hapus lama jika ada
                if ($detail->foto_profile && Storage::exists('public/' . $detail->foto_profile)) {
                    Storage::delete('public/' . $detail->foto_profile);
                }
                $path = $request->file('foto_profile')->store('foto_profile', 'public');
                $dataDetail['foto_profile'] = $path;
            }

            // Simpan update ke model detail
            $detail->update($dataDetail);

            return response()->json([
                'message' => 'Profil berhasil diperbarui.',
                'foto_profile' => $detail->foto_profile ? asset('storage/' . $detail->foto_profile) : null,
                'redirect' => route('profile.index') // direct ke halaman awal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
