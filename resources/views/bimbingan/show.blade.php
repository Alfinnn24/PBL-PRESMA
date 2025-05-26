@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="d-flex justify-content-between align-items-center mb-3 p-2">
            <h5>Prestasi Mahasiswa: {{ $mahasiswa->nama_lengkap }} ({{ $mahasiswa->nim }})</h5>
            <a href="{{ url('/dosen/bimbingan') }}" class="btn btn-secondary btn-sm">← Kembali ke Daftar</a>
        </div>
        <div class="card-body">
            {{-- Filter Tahun --}}
            <form method="GET">
                <select name="tahun" onchange="this.form.submit()" class="form-select form-select-sm">
                    <option value="">-- Semua Tahun --</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Tabel Prestasi --}}
            <div class="table-responsive">
                <table id="table_prestasi" class="table table-bordered table-striped table-hover table-sm display nowrap"
                    style="width:100%">
                    <thead class="table-light">
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
    @push('js')
        <script>
            $(document).ready(function () {
                $('#table_prestasi').DataTable({
                    paging: true,
                    searching: false,
                    ordering: false,
                    info: true,
                    responsive: true,
                    lengthChange: false, // ← ini menonaktifkan "Show entries"
                    language: {
                        zeroRecords: "Tidak ada prestasi ditemukan",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data tersedia",
                        infoFiltered: "(difilter dari total _MAX_ data)",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection