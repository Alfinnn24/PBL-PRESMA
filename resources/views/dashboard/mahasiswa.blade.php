@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #007bff;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;"><i class="fas fa-trophy"></i></div>
                            <div class="inner">
                                <h4>{{ $jumlahPrestasi }}</h4>
                                <p>Jumlah Prestasi</p>
                            </div>
                        </div>
                        <a href="/prestasi" class="small-box-footer text-white">Selengkapnya <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #28a745;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;"><i class="fas fa-check-circle"></i></div>
                            <div class="inner">
                                <h4>{{ $jumlahLombaDisetujui }}</h4>
                                <p>Lomba Disetujui</p>
                            </div>
                        </div>
                        <a href="/pendaftaran" class="small-box-footer text-white">Selengkapnya <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #ffc107;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;"><i class="fas fa-list"></i></div>
                            <div class="inner">
                                <h4>{{ $jumlahPendaftaran }}</h4>
                                <p>Total Pendaftaran</p>
                            </div>
                        </div>
                        <a href="/pendaftaran" class="small-box-footer text-white">Selengkapnya <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Top 3 Rekomendasi Lomba</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Lomba</th>
                                <th>Hasil Rekomendasi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekomendasiTop as $rek)
                                <tr>
                                    <td>{{ $rek->lomba->nama ?? '-' }}</td>
                                    <td>
                                        @php
                                            $skor = $rek->skor;
                                            if ($skor >= 0.85)
                                                $label = 'Sangat Direkomendasikan';
                                            elseif ($skor >= 0.7)
                                                $label = 'Direkomendasikan';
                                            elseif ($skor >= 0.4)
                                                $label = 'Cukup Direkomendasikan';
                                            else
                                                $label = 'Tidak Direkomendasikan';
                                        @endphp
                                        {{ $label }}
                                    </td>
                                    <td>
                                        <a href="/rekomendasi" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data rekomendasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>3 Lomba Terdaftar Terakhir</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Lomba</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftaranTerbaru as $daftar)
                                <tr>
                                    <td>{{ $daftar->lomba->nama ?? '-' }}</td>
                                    <td>{{ $daftar->status }}</td>
                                    <td>
                                        <a href="/pendaftaran" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada pendaftaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection