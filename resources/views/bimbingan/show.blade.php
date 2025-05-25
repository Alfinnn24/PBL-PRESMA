@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center px-4 py-3">
            <h3 class="card-title mb-0">
                Prestasi Mahasiswa: {{ $mahasiswa->nama_lengkap }} ({{ $mahasiswa->nim }})
            </h3>
            <a href="{{ url('/dosen/bimbingan') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
        </div>
        <div class="card-body">
            {{-- Filter Tahun --}}
            <form method="GET" class="mb-3 d-flex align-items-center gap-2 flex-wrap">
                <label for="tahun" class="mb-0 me-2">Filter Tahun:</label>
                <select name="tahun" id="tahun" class="form-select" style="width: auto; min-width: 150px;">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>{{ $tahun }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>

            {{-- Tabel Prestasi --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm mb-0">
                    <thead class="table-light">
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
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $prestasis->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection