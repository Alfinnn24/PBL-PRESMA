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
            @if($rekomendasi->isEmpty())
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
                            @foreach($rekomendasi as $item)
                                <tr>
                                    <td>{{ $item->lomba->nama }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($item->status == 'Pending') badge-warning
                                            @elseif($item->status == 'Disetujui') badge-success
                                            @elseif($item->status == 'Ditolak') badge-danger
                                            @endif py-2 px-3" style="font-size: 0.9rem;">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status == 'Pending')
                                            <form action="{{ route('rekomendasi.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" name="status" value="Disetujui" class="btn btn-sm btn-success mb-1">
                                                    <i class="fas fa-check-circle"></i> Setujui
                                                </button>
                                                <button type="submit" name="status" value="Ditolak" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times-circle"></i> Tolak
                                                </button>
                                            </form>
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
