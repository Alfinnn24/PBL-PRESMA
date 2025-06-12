@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="d-flex justify-content-between align-items-center mb-3 p-2">
            <h5>Prestasi Mahasiswa: {{ $mahasiswa->nama_lengkap }} ({{ $mahasiswa->nim }})</h5>
            <a href="{{ url('/dosen/bimbingan') }}" class="btn btn-secondary btn-sm">← Kembali ke Daftar</a>
        </div>

        <div class="card-body">
            {{-- Filter Tahun --}}
            <!-- <form method="GET" class="mb-3">
                <div style="max-width: 200px;">
                    <select name="tahun" onchange="this.form.submit()" class="form-select form-select-sm">
                        <option value="">-- Semua Tahun --</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form> -->

            {{-- Tabel Prestasi --}}
            <div style="overflow-x:auto;">
                <table id="table_prestasi" class="table modern-table display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Prestasi</th>
                            <th>Nama Lomba</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prestasis as $i => $detailPrestasi)
                            <tr>
                                <td>{{ $prestasis->firstItem() + $i }}</td>
                                <td>{{ $detailPrestasi->prestasi->nama_prestasi ?? '-' }}</td>
                                <td>{{ $detailPrestasi->prestasi->lomba->nama ?? '-' }}</td>
                                <td>{{ optional($detailPrestasi->prestasi->created_at)->format('Y') ?? '-' }}</td>
                                <td>{{ ucfirst($detailPrestasi->prestasi->status ?? '-') }}</td>
                                <td>{{ $detailPrestasi->prestasi->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada prestasi yang ditemukan.</td>
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

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            $('#table_prestasi').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                responsive: true,
                language: {
                    zeroRecords: "Tidak ada prestasi ditemukan"
                }
            });
        });
    </script>
@endpush
