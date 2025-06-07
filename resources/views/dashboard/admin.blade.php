@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                {{-- jumlah prestasi --}}
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
                            <span class="small">More info</span>
                            <i class="fas fa-arrow-circle-right ms-1"></i>
                        </a>
                    </div>
                </div>
                {{-- bidang terbanyak --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-success text-white rounded-lg shadow-sm">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $bidangTerbanyak ?? '-' }}</h3>
                                <p class="mb-0 small">Bidang Terbanyak</p>
                            </div>
                        </div>
                        <a href="/prestasi" class="small-box-footer d-block text-white text-decoration-none py-2 px-3">
                            <span class="small">More info</span>
                            <i class="fas fa-arrow-circle-right ms-1"></i>
                        </a>
                    </div>
                </div>
                {{-- efektivitas rekomendasi --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-warning rounded-lg shadow-sm" style="color: #fff !important;">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $efektivitas }}%</h3>
                                <p class="mb-0 small">Efektivitas Rekomendasi</p>
                            </div>
                        </div>
                        <a href="/rekomendasi" class="small-box-footer d-block text-white text-decoration-none py-2 px-3">
                            <span class="small" style="color: #fff !important;">More info</span>
                            <i class="fas fa-arrow-circle-right ms-1" style="color: #fff !important;"></i>
                        </a>
                    </div>
                </div>
            </div>
            {{-- prestasi terbaru --}}
            <div class="row mb-4">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="mb-0 text-primary">
                                <i class="nav-icon fas fa-solid fa-award"></i> Prestasi Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold">Nama Prestasi</th>
                                            <th class="border-0 fw-semibold">Lomba</th>
                                            <th class="border-0 text-center fw-semibold" style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prestasiTerbaru as $item)
                                            <tr>
                                                <td class="border-0 align-middle">{{ $item->nama_prestasi }}</td>
                                                <td class="border-0 align-middle">{{ $item->lomba->nama ?? '-' }}</td>
                                                <td class="border-0 text-center align-middle">
                                                    <a href="/prestasi" class="btn btn-sm btn-info">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
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
                                <i class="nav-icon fas fa-solid fa-folder-open"></i> Lomba Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold">Nama Lomba</th>
                                            <th class="border-0 fw-semibold">Tanggal Mulai</th>
                                            <th class="border-0 text-center fw-semibold" style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lombaTerbaru as $item)
                                            <tr>
                                                <td class="border-0 align-middle">{{ $item->nama }}</td>
                                                <td class="border-0 align-middle">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                                                <td class="border-0 text-center align-middle">
                                                    <a href="/lomba" class="btn btn-sm btn-info">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- grafik --}}
            <div class="row mb-4">
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-chart-bar me-2"></i> Prestasi per Periode
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="grafikPrestasiPeriode" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-chart-pie me-2"></i> Prestasi per Bidang
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="grafikPrestasiBidang" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 text-primary">
                    <h5>Ranking Mahasiswa dengan Prestasi Terbanyak</h5>
                    <div class="table-responsive">
                        <table class="table modern-table display nowrap align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 60px;">#</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th class="text-center">Jumlah Prestasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rankingMahasiswa as $i => $rank)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $rank->mahasiswa_nim }}</td>
                                        <td>{{ $rank->nama_lengkap ?? '-' }}</td>
                                        <td class="text-center">{{ $rank->total }}</td>
                                    </tr>
                                @endforeach
                                @if ($rankingMahasiswa->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data ranking mahasiswa.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- css buat tabel, NOTE: CLASS TABLE JADI GINI "table modern-table display nowrap" --}}
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
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
            font-size: 0.8rem;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
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
    </style>
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxPeriode = document.getElementById('grafikPrestasiPeriode').getContext('2d');
        new Chart(ctxPeriode, {
            type: 'bar',
            data: {
                labels: {!! json_encode($prestasiPerPeriode->keys()) !!},
                datasets: [{
                    label: 'Jumlah Prestasi per Periode',
                    data: {!! json_encode($prestasiPerPeriode->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            }
        });

        const ctxBidang = document.getElementById('grafikPrestasiBidang').getContext('2d');
        new Chart(ctxBidang, {
            type: 'pie',
            data: {
                labels: {!! json_encode($prestasiPerBidang->keys()) !!},
                datasets: [{
                    label: 'Prestasi per Bidang',
                    data: {!! json_encode($prestasiPerBidang->values()) !!},
                    backgroundColor: [
                        '#f44336', '#2196f3', '#4caf50', '#ff9800', '#9c27b0', '#00bcd4', '#8bc34a'
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right' // label di samping chart
                    }
                }
            }
        });
    </script>
@endpush
