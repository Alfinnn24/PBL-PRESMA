@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Laporan & Analisis Prestasi Mahasiswa</h3>
            <div class="card-tools">
                <a href="{{ route('laporan-prestasi.exportExcel', request()->all()) }}" class="btn btn-sm btn-success mt-1">Export Excel</a>
                <a href="{{ route('laporan-prestasi.exportPdf', request()->all()) }}" class="btn btn-sm btn-danger mt-1">Export PDF</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Filter Form -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>

                        <div class="col-3">
                            <select class="form-control" id="filterBidangKeahlian" name="bidang_keahlian">
                                <option value="" {{ request('bidang_keahlian') == '' ? 'selected' : '' }}>Semua</option>
                                @foreach($bidangKeahlianList as $bidang)
                                    <option value="{{ $bidang->keahlian }}" {{ request('bidang_keahlian') == $bidang->keahlian ? 'selected' : '' }}>
                                        {{ $bidang->keahlian }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Bidang Keahlian</small>
                        </div>

                        <div class="col-3">
                            <select class="form-control" id="filterTingkat" name="tingkat">
                                <option value="" {{ request('tingkat') == '' ? 'selected' : '' }}>Semua</option>
                                <option value="Kota/Kabupaten" {{ request('tingkat') == 'Kota/Kabupaten' ? 'selected' : '' }}>Kota/Kabupaten</option>
                                <option value="Nasional" {{ request('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ request('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                            <small class="form-text text-muted">Tingkat</small>
                        </div>

                        <div class="col-3">
                            <select class="form-control" id="filterTahunPrestasi" name="tahun_prestasi">
                                <option value="" {{ request('tahun_prestasi') == '' ? 'selected' : '' }}>Semua</option>
                                <option value="2024" {{ request('tahun_prestasi') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ request('tahun_prestasi') == '2025' ? 'selected' : '' }}>2025</option>
                            </select>
                            <small class="form-text text-muted">Tahun Prestasi</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="table modern-table display nowrap" id="table_laporan" style="width:100%">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Lomba</th>
                        <th>Bidang Keahlian</th>
                        <th>Tingkat</th>
                        <th>Tahun Prestasi</th>
                    </tr>
                </thead>
            </table>

            <!-- Chart Container: letakkan di bawah tabel -->
            <div class="mt-5">
                <h5 class="text-center">Diagram Batang: Prestasi Berdasarkan Bidang Keahlian & Tahun</h5>
                <canvas id="barChart" style="max-height: 300px;"></canvas>
            </div>
            <div class="mt-4">
                <h5 class="text-center">Diagram Lingkaran: Prestasi Berdasarkan Bidang Keahlian & Tahun</h5>
                <canvas id="pieChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        /* Styling tombol pagination DataTables */
        .dataTables_paginate .paginate_button {
            border-radius: 4px;
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff !important;
            border: 1px solid #007bff;
            transition: background-color 0.3s ease;
        }
        .dataTables_paginate .paginate_button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .dataTables_paginate .paginate_button.disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }
        .dataTables_paginate .paginate_button.current {
            background-color: #0056b3;
            border-color: #0056b3;
            color: #fff !important;
        }
    </style>
@endpush

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            var table = $('#table_laporan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('laporan-prestasi.list') }}",
                    type: "POST",
                    data: function (d) {
                        d.bidang_keahlian = $('#filterBidangKeahlian').val();
                        d.tingkat = $('#filterTingkat').val();
                        d.tahun_prestasi = $('#filterTahunPrestasi').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    { data: 'mahasiswa', name: 'mahasiswa', className: 'text-center' },
                    { data: 'lomba', name: 'lomba', className: 'text-center' },
                    { data: 'bidang_keahlian', name: 'bidang_keahlian', className: 'text-center' },
                    { data: 'tingkat', name: 'tingkat', className: 'text-center' },
                    { data: 'tahun_prestasi', name: 'tahun_prestasi', className: 'text-center' }
                ],
                scrollX: true,
                language: {
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya'
                }
            });

            // Reload table saat filter berubah
            $('#filterBidangKeahlian, #filterTingkat, #filterTahunPrestasi').on('change', function () {
                table.ajax.reload();
            });

            // Data statistik gabungan dari controller
            const statistikGabungan = @json($statistikGabungan);

            const labels = Object.keys(statistikGabungan);
            const dataValues = Object.values(statistikGabungan);

            // Generate warna random untuk pie chart
            const backgroundColors = labels.map(() => {
                return 'hsl(' + Math.floor(Math.random() * 360) + ', 70%, 60%)';
            });

            // Chart Batang: dengan pengaturan rapih
            const ctxBar = document.getElementById('barChart').getContext('2d');
            const barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Prestasi',
                        data: dataValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        barPercentage: 0.6,
                        categoryPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            bottom: 30
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 30,
                                autoSkip: false,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });

            // Chart Lingkaran (Pie)
            const ctxPie = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Distribusi Prestasi',
                        data: dataValues,
                        backgroundColor: backgroundColors,
                        hoverOffset: 30
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        });
    </script>
@endpush
