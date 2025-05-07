@extends('layouts.template')

@section('content')
    <div class="container">

        @if($rekomendasi->isEmpty())
            <p>Tidak ada rekomendasi lomba.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Lomba</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekomendasi as $item)
                        <tr>
                            <td>{{ $item->lomba->nama }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                @if($item->status == 'Pending')
                                    <form action="{{ route('rekomendasi.updateStatus', $item->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" name="status" value="Disetujui" class="btn btn-success">Setujui</button>
                                        <button type="submit" name="status" value="Ditolak" class="btn btn-danger">Tolak</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection