<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\AdminModel;

class AuthController extends Controller
{
    public function landing(){
        return view('landing');
    }
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validatedData = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        // Role langsung ditentukan atau bisa dari form dropdown
        $roles = ['admin', 'mahasiswa', 'dosen'];

        return view('auth.register', ['roles' => $roles]);
    }

    public function postregister(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => 'required|string|min:3|unique:user,username',
                'nama' => 'required|string|min:3',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|string|min:5',
                'role' => 'required|in:admin,mahasiswa,dosen'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'msgField' => $validator->errors()
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
                    'nama_lengkap' => $request->nama,
                    'foto_profile' => null // null soalnya kosong, nanti bisa diubah defaultnya
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
                'redirect' => url('/login'),
            ]);
        }
    }
}
