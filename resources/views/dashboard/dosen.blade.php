@extends('layouts.template')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            {{-- statistik --}}
            <div class="row mb-4">
                {{-- jumlah mahasiswa bimbingan --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-info text-white rounded-lg shadow-sm">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $jumlahMahasiswaBimbingan }}</h3>
                                <p class="mb-0 small">Mahasiswa Bimbingan</p>
                            </div>
                        </div>
                        <a href="/dosen/bimbingan"
                            class="small-box-footer d-block text-white text-decoration-none py-2 px-3">
                            <span class="small">Selengkapnya</span>
                            <i class="fas fa-arrow-circle-right ms-1"></i>
                        </a>
                    </div>
                </div>

                {{-- jumlah rekomendasi dosen --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-success text-white rounded-lg shadow-sm">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $jumlahRekomendasiDosen }}</h3>
                                <p class="mb-0 small">Rekomendasi Diajukan</p>
                            </div>
                        </div>
                        <a href="/rekomendasi" class="small-box-footer d-block text-white text-decoration-none py-2 px-3">
                            <span class="small">Selengkapnya</span>
                            <i class="fas fa-arrow-circle-right ms-1"></i>
                        </a>
                    </div>
                </div>

                {{-- persentase lomba disetujui --}}
                <div class="col-md-4 mb-3">
                    <div class="small-box bg-warning rounded-lg shadow-sm" style="color: #fff !important;">
                        <div class="d-flex align-items-center p-3">
                            <div class="icon me-3" style="font-size: 2.5rem; opacity: 0.8;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="inner flex-grow-1">
                                <h3 class="mb-1 fw-bold">{{ $persentaseDisetujui }}%</h3>
                                <p class="mb-0 small">Lomba Disetujui</p>
                            </div>
                        </div>
                        <a href="/dosen/lomba" class="small-box-footer d-block text-decoration-none py-2 px-3"
                            style="color: #fff !important;">
                            <span class="small">Selengkapnya</span>
                            <i class="fas fa-arrow-circle-right ms-1" style="color: #fff !important;"></i>
                        </a>
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
