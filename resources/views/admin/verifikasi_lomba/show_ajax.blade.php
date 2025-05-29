@empty($lomba)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang anda cari tidak ditemukan
            </div>
            <a href="{{ url('/verifikasi_lomba') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Lomba</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th width="25%">ID Lomba</th>
                    <td>{{ $lomba->id }}</td>
                </tr>
                <tr>
                    <th>Nama Lomba</th>
                    <td>{{ $lomba->nama }}</td>
                </tr>
                <tr>
                    <th>Penyelenggara Lomba</th>
                    <td>{{ $lomba->penyelenggara }}</td>
                </tr>
                <tr>
                    <th>Tingkat Lomba</th>
                    <td>{{ $lomba->tingkat }}</td>
                </tr>
                <tr>
                    <th>Bidang Keahlian Lomba</th>
                    <td>{{ $lomba->bidangKeahlian->keahlian }}</td>
                </tr>
                <tr>
                    <th>Persyaratan Lomba</th>
                    <td>{{ $lomba->persyaratan }}</td>
                </tr>
                <tr>
                    <th>Jumlah Peserta Lomba</th>
                    <td>{{ $lomba->jumlah_peserta }}</td>
                </tr>
                <tr>
                    <th>Link Registrasi Lomba</th>
                    <td>{{ $lomba->link_registrasi }}</td>
                </tr>
                <tr>
                    <th>Tanggal Mulai Lomba</th>
                    <td>{{ \Carbon\Carbon::parse($lomba->tanggal_mulai)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Tanggal Selesai Lomba</th>
                    <td>{{ \Carbon\Carbon::parse($lomba->tanggal_selesai)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Periode Lomba</th>
                    <td>{{ $lomba->periode->display_name }}</td>
                </tr>
                <tr>
                    <th>Pembuat Lomba</th>
                    <td>{{ $lomba->creator->full_name ?? 'Tidak Diketahui'  }}</td>
                </tr>
                <tr>
                    <th>Status Lomba</th>
                    <td>{{ $lomba->is_verified }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>
@endempty
