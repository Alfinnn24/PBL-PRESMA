@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #17a2b8;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="inner">
                                <h4>{{ $jumlahMahasiswaBimbingan }}</h4>
                                <p>Mahasiswa Bimbingan</p>
                            </div>
                        </div>
                        <a href="/dosen/bimbingan" class="small-box-footer text-white">
                            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #28a745;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="inner">
                                <h4>{{ $jumlahRekomendasiDosen }}</h4>
                                <p>Rekomendasi Diajukan</p>
                            </div>
                        </div>
                        <a href="/rekomendasi" class="small-box-footer text-white">
                            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box p-3 text-white" style="background-color: #ffc107;">
                        <div class="d-flex align-items-center">
                            <div class="icon me-3" style="font-size:2rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="inner">
                                <h4>{{ $persentaseDisetujui }}%</h4>
                                <p>Lomba Disetujui</p>
                            </div>
                        </div>
                        <a href="/dosen/lomba" class="small-box-footer text-white">
                            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection