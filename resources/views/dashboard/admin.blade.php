@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #007bff;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="inner">
                                <h4>{{ $jumlahPrestasi }}</h4>
                                <p>Jumlah Prestasi</p>
                            </div>
                        </div>
                        <a href="/prestasi" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #28a745;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="inner">
                                <h4>{{ $bidangTerbanyak ?? '-' }}</h4>
                                <p>Bidang Terbanyak</p>
                            </div>
                        </div>
                        <a href="/prestasi" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #ffc107;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="inner">
                                <h4>{{ $efektivitas }}%</h4>
                                <p>Efektivitas Rekomendasi</p>
                            </div>
                        </div>
                        <a href="/rekomendasi" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Prestasi Terbaru</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Prestasi</th>
                                <th>Lomba</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prestasiTerbaru as $item)
                                <tr>
                                    <td>{{ $item->nama_prestasi }}</td>
                                    <td>{{ $item->lomba->nama ?? '-' }}</td>
                                    <td>
                                        <a href="/prestasi" class="btn btn-sm btn-info">Selengkapnya</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Lomba Terbaru</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Lomba</th>
                                <th>Tanggal Mulai</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lombaTerbaru as $item)
                                <tr>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                                    <td>
                                        <a href="/lomba" class="btn btn-sm btn-info">Selengkapnya</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-4" style="min-height: auto;">
                <div class="col-md-6 d-flex align-items-stretch">
                    <canvas id="grafikPrestasiPeriode"></canvas>
                </div>
                <div class="col-md-6 d-flex align-items-stretch">
                    <canvas id="grafikPrestasiBidang"></canvas>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5>Ranking Mahasiswa dengan Prestasi Terbanyak</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center" style="width: 60px;">#</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th class="text-center">Jumlah Prestasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rankingMahasiswa as $i => $rank)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $rank->mahasiswa_nim }}</td>
                                        <td>{{ $rank->nama_lengkap ?? '-' }}</td>
                                        <td class="text-center">{{ $rank->total }}</td>
                                    </tr>
                                @endforeach
                                @if($rankingMahasiswa->isEmpty())
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