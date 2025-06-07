@extends('layouts.template')

@section('content')
    <div class="card shadow-sm">
        {{-- statistik --}}
        <div class="card-body">
            {{-- jumlah prestasi --}}
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-primary text-white rounded-lg shadow-sm">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $jumlahPrestasi }}</h3>
                                <p class="mb-0 small">Jumlah Prestasi</p>
                            </div>
                        </div>
                        <a href="/prestasi" class="small-box-footer d-block text-white text-decoration-none py-2 px-3">
                            <span class="small">Selengkapnya</span>
                            <i class="fas fa-arrow-circle-right ms-1"></i>
                        </a>
                    </div>
                </div>
                {{-- jumlah lomba --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-success text-white rounded-lg shadow-sm">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $jumlahLombaDisetujui }}</h3>
                                <p class="mb-0 small">Lomba Disetujui</p>
                            </div>
                        </div>
                        <a href="/pendaftaran" class="small-box-footer d-block text-white text-decoration-none py-2 px-3">
                            <span class="small">Selengkapnya</span>
                            <i class="fas fa-arrow-circle-right ms-1"></i>
                        </a>
                    </div>
                </div>
                {{-- jumlah pendaftaran --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-warning rounded-lg shadow-sm" style="color: #fff !important;">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $jumlahPendaftaran }}</h3>
                                <p class="mb-0 small">Total Pendaftaran</p>
                            </div>
                        </div>
                        <a href="/pendaftaran" class="small-box-footer d-block text-decoration-none py-2 px-3"
                            style="color: #fff !important;">
                            <span class="small">Selengkapnya</span>
                            <i class="fas fa-arrow-circle-right ms-1" style="color: #fff !important;"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- rekomendasi lomba --}}
            <div class="row mb-4">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-star me-2"></i> Top 3 Rekomendasi Lomba
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold">Nama Lomba</th>
                                            <th class="border-0 fw-semibold">Hasil Rekomendasi</th>
                                            <th class="border-0 text-center fw-semibold" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rekomendasiTop as $rek)
                                            <tr>
                                                <td class="border-0 align-middle">{{ $rek->lomba->nama ?? '-' }}</td>
                                                <td class="border-0 align-middle">
                                                    @php
                                                        $skor = $rek->skor;
                                                        if ($skor >= 0.85) {
                                                            $label = 'Sangat Direkomendasikan';
                                                            $badgeClass = 'bg-success';
                                                        } elseif ($skor >= 0.7) {
                                                            $label = 'Direkomendasikan';
                                                            $badgeClass = 'bg-primary';
                                                        } elseif ($skor >= 0.4) {
                                                            $label = 'Cukup Direkomendasikan';
                                                            $badgeClass = 'bg-warning';
                                                        } else {
                                                            $label = 'Tidak Direkomendasikan';
                                                            $badgeClass = 'bg-danger';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="badge {{ $badgeClass }} px-2 py-1">{{ $label }}</span>
                                                </td>
                                                <td class="border-0 text-center align-middle">
                                                    <a href="/rekomendasi" class="btn btn-sm btn-primary">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="border-0 text-center py-4 text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Tidak ada data rekomendasi.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-clipboard-list me-2"></i> 3 Lomba Terdaftar Terakhir
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold">Nama Lomba</th>
                                            <th class="border-0 fw-semibold">Status</th>
                                            <th class="border-0 text-center fw-semibold" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendaftaranTerbaru as $daftar)
                                            <tr>
                                                <td class="border-0 align-middle">{{ $daftar->lomba->nama ?? '-' }}</td>
                                                <td class="border-0 align-middle">
                                                    @php
                                                        $status = $daftar->status;
                                                        $badgeClass = match (strtolower($status)) {
                                                            'disetujui', 'approved' => 'bg-success',
                                                            'pending', 'menunggu' => 'bg-warning text-dark',
                                                            'ditolak', 'rejected' => 'bg-danger',
                                                            default => 'bg-secondary',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge {{ $badgeClass }} px-2 py-1">{{ $status }}</span>
                                                </td>
                                                <td class="border-0 text-center align-middle">
                                                    <a href="/pendaftaran" class="btn btn-sm btn-primary">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="border-0 text-center py-4 text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Belum ada pendaftaran.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        .small-box {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .small-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .small-box-footer {
            background: rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease;
        }

        .small-box-footer:hover {
            background: rgba(0, 0, 0, 0.2);
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .table th {
            font-weight: 600;
            color: #495057;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .rounded-lg {
            border-radius: 0.5rem !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fw-medium {
            font-weight: 500 !important;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush
