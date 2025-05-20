@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card shadow">
            <div class="card-header">
                <h3>Edit Profil</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <!-- Upload Foto Profil dengan Preview dan Overlay -->
                    <div class="form-group text-center">
                        <div class="d-flex flex-column align-items-center mb-3">
                            <strong class="mb-2">Upload Foto Profil</strong>
                            <label class="position-relative" for="profile_picture">
                                <div style="width: 120px; height: 120px; position: relative;">
                                    <img id="preview-image"
                                        src="{{ $detail->foto_profile ? asset('storage/' . $detail->foto_profile) : asset('images/default-profile.png') }}"
                                        alt="Foto Profil" class="rounded-circle"
                                        style="width: 100%; height: 100%; object-fit: cover; object-position: center;">

                                    <div class="overlay rounded-circle"
                                        style="
                                        opacity: 0;
                                        transition: opacity 0.15s;
                                        cursor: pointer;
                                        position: absolute;
                                        top: 0; left: 0;
                                        width: 100%; height: 100%;
                                        background: rgba(0,0,0,0.4);"
                                        onmouseover="this.style.opacity = 1;" onmouseout="this.style.opacity = 0;">
                                        <i class="fas fa-upload position-absolute text-white"
                                            style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <input type="file" name="foto_profile" id="profile_picture" class="d-none"
                            accept="image/jpeg, image/jpg, image/png">
                        @error('foto_profile')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $detail->nama_lengkap) }}">
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($user->role === 'mahasiswa')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>NIM</label>
                                <input type="text" class="form-control" value="{{ $detail->nim }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Angkatan</label>
                                <input type="text" name="angkatan"
                                    class="form-control @error('angkatan') is-invalid @enderror"
                                    value="{{ old('angkatan', $detail->angkatan) }}">
                                @error('angkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Program Studi</label>
                                <select name="program_studi_id"
                                    class="form-control @error('program_studi_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('program_studi_id', $detail->program_studi_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_studi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>No. Telp</label>
                                <input type="text" name="no_telp"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp', $detail->no_telp) }}">
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Alamat</label>
                                <input type="text" name="alamat"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat', $detail->alamat) }}">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @elseif($user->role === 'dosen')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>NIDN</label>
                                <input type="text" class="form-control" value="{{ $detail->nidn }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Program Studi</label>
                                <select name="program_studi_id"
                                    class="form-control @error('program_studi_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('program_studi_id', $detail->program_studi_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_studi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>No. Telp</label>
                                <input type="text" name="no_telp"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp', $detail->no_telp) }}">
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="form-group">
                        <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <label class="mt-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
