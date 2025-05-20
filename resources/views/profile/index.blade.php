@extends('layouts.template')

@section('content')
    {{-- background profile BG --}}
    <div class="profile-header"
        style="background: url('{{ asset('images/bgprofile.png') }}') center/cover no-repeat; height: 200px;">
    </div>

    {{-- profile content --}}
    <div class="container mt-n5">
        <div class="card shadow">
            <div class="card-body text-center">

                {{-- Foto Profil --}}
                <div class="form-group text-center">
                    <div class="d-flex flex-column align-items-center mb-3">
                        <div style="width: 120px; height: 120px; position: relative;">
                            <img src="{{ $detail->foto_profile ? asset('storage/' . $detail->foto_profile) : asset('images/default-profile.png') }}"
                                alt="Foto Profil" class="rounded-circle border"
                                style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        </div>
                    </div>
                </div>

                {{-- nama lengkap --}}
                <h4 class="mt-3">{{ $detail->nama_lengkap }}</h4>
                <p class="text-muted">
                    {{ $user->role === 'mahasiswa' ? $detail->nim : ($user->role === 'dosen' ? $detail->nidn : $user->id) }}
                </p>

                <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>

                <div class="row text-left mt-4">
                    <div class="col-md-6">
                        <h5>Personal Information</h5>
                        @if ($user->role === 'mahasiswa')
                            <p><strong>Program Studi:</strong> {{ $detail->programStudi->nama_prodi ?? '-' }}</p>
                            <p><strong>Angkatan:</strong> {{ $detail->angkatan }}</p>
                            <p><strong>No. HP:</strong> {{ $detail->no_telp }}</p>
                            <p><strong>Alamat:</strong> {{ $detail->alamat }}</p>
                        @elseif($user->role === 'dosen')
                            <p><strong>Program Studi:</strong> {{ $detail->programStudi->nama ?? '-' }}</p>
                            <p><strong>No. HP:</strong> {{ $detail->no_telp }}</p>
                        @endif
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                    </div>
                    {{-- @if (isset($detail->minat) || isset($detail->keahlian))
                        <div class="col-md-6">
                            <h5>Minat &amp; Keahlian</h5>
                            <p><strong>Minat:</strong> {{ $detail->minat ?? '-' }}</p>
                            <p><strong>Keahlian:</strong> {{ $detail->keahlian ?? '-' }}</p>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
@endsection
