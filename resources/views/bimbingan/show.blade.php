@extends('layouts.template')

@section('content')
<h1>Prestasi Mahasiswa: {{ $mahasiswa->nama_lengkap }} ({{ $mahasiswa->nim }})</h1>
<a href="{{ url('/dosen/bimbingan') }}" class="btn btn-secondary mb-3">Kembali ke Daftar</a>

{{-- Filter Tahun (Kategori dihilangkan karena tidak ada) --}}
<form method="GET" class="form-inline mb-3">
    {{-- Jika kategori tidak ada, bisa disembunyikan atau hapus --}}
    {{-- 
    <label for="kategori" class="mr-2">Filter Kategori:</label>
    <select name="kategori" id="kategori" class="form-control mr-3">
        <option value="">-- Semua Kategori --</option>
        @foreach($kategoriList as $kat)
        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
        @endforeach
    </select>
    --}}
    
    <label for="tahun" class="mr-2">Filter Tahun:</label>
    <select name="tahun" id="tahun" class="form-control mr-3">
        <option value="">-- Semua Tahun --</option>
        @foreach($tahunList as $tahun)
        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nama Prestasi</th>
            <th>Nama Lomba</th>
            <th>Tahun</th>
            <th>Status</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($prestasis as $detailPrestasi)
        <tr>
            <td>{{ $detailPrestasi->prestasi->nama_prestasi ?? '-' }}</td>
            <td>{{ $detailPrestasi->prestasi->lomba->nama ?? '-' }}</td>
            <td>{{ optional($detailPrestasi->prestasi->created_at)->format('Y') ?? '-' }}</td>
            <td>{{ ucfirst($detailPrestasi->prestasi->status ?? '-') }}</td>
            <td>{{ $detailPrestasi->prestasi->catatan ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Belum ada prestasi yang ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $prestasis->links() }}

@endsection
