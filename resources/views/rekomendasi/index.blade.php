@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mt-4 border-0">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-trophy mr-2"></i> Rekomendasi Lomba
                </h3>
            </div>
            <div class="card-body p-2">
                @if ($rekomendasi->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Tidak ada rekomendasi lomba.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Lomba</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekomendasi as $item)
                                    <tr>
                                        <td>{{ $item->lomba->nama }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                            @if ($item->status == 'Pending') badge-warning
                                            @elseif($item->status == 'Disetujui') badge-success
                                            @elseif($item->status == 'Ditolak') badge-danger @endif py-2 px-3"
                                                style="font-size: 0.9rem;">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'Pending')
                                                <button type="button" class="btn btn-sm btn-success"
                                                    data-toggle="modal" data-target="#konfirmasiSetuju{{ $item->id }}">
                                                    <i class="fas fa-check-circle"></i> Setujui
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#konfirmasiTolak{{ $item->id }}">
                                                    <i class="fas fa-times-circle"></i> Tolak
                                                </button>

                                                <!-- Modal Konfirmasi Setuju -->
                                                <div class="modal fade" id="konfirmasiSetuju{{ $item->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="konfirmasiSetujuLabel{{ $item->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content border-0 shadow-sm">
                                                            <div class="modal-body text-center p-4">
                                                                <div class="mb-4">
                                                                    <div class="mx-auto"
                                                                        style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(40, 167, 69, 0.1); display: flex; align-items: center; justify-content: center;">
                                                                        <i class="fas fa-check"
                                                                            style="font-size: 40px; color: #28a745;"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="font-weight-bold mb-3">Berhasil</h5>
                                                                <p class="text-muted mb-4">Apakah Anda yakin ingin
                                                                    menyetujui rekomendasi lomba
                                                                    <strong>{{ $item->lomba->nama }}</strong>?
                                                                </p>

                                                                <form
                                                                    action="{{ route('rekomendasi.updateStatus', $item->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <button type="submit" name="status" value="Disetujui"
                                                                        class="btn btn-primary px-4">
                                                                        OK
                                                                    </button>
                                                                </form>
                                                                <button type="button" class="btn btn-light ml-2"
                                                                    data-dismiss="modal">Batal</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Konfirmasi Tolak -->
                                                <div class="modal fade" id="konfirmasiTolak{{ $item->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="konfirmasiTolakLabel{{ $item->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content border-0 shadow-sm">
                                                            <div class="modal-body text-center p-4">
                                                                <div class="mb-4">
                                                                    <div class="mx-auto"
                                                                        style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center;">
                                                                        <i class="fas fa-times"
                                                                            style="font-size: 40px; color: #dc3545;"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="font-weight-bold mb-3">Konfirmasi</h5>
                                                                <p class="text-muted mb-4">Apakah Anda yakin ingin menolak
                                                                    rekomendasi lomba
                                                                    <strong>{{ $item->lomba->nama }}</strong>?
                                                                </p>

                                                                <form
                                                                    action="{{ route('rekomendasi.updateStatus', $item->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <button type="submit" name="status" value="Ditolak"
                                                                        class="btn btn-primary px-4">
                                                                        OK
                                                                    </button>
                                                                </form>
                                                                <button type="button" class="btn btn-light ml-2"
                                                                    data-dismiss="modal">Batal</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
