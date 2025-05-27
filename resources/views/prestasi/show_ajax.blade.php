@empty($prestasi)
    <div id="myModal" class="modal-dialog modal-lg" role="document">
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
                <a href="{{ url('/prestasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Prestasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Informasi Prestasi</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="25%">ID Prestasi</th>
                        <td>{{ $prestasi->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Prestasi</th>
                        <td>{{ $prestasi->nama_prestasi }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $prestasi->status }}</td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $prestasi->catatan }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td>{{ $prestasi->creator->fullName }}</td>
                    </tr>
                    </tr>
                    <tr>
                        <th>Bukti Prestasi</th>
                        <td>
                            <a href="{{ asset($prestasi->file_bukti) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                Bukti</a>
                        </td>
                    </tr>
                </table>

                <h5 class="mt-4">Informasi Lomba</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Lomba</th>
                        <td>{{ $prestasi->lomba->nama }}</td>
                    </tr>
                    <tr>
                        <th>Penyelenggara</th>
                        <td>{{ $prestasi->lomba->penyelenggara }}</td>
                    </tr>
                    <tr>
                        <th>Tingkat</th>
                        <td>{{ $prestasi->lomba->tingkat }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Lomba</th>
                        <td>{{ $prestasi->lomba->bidangKeahlian->keahlian }}</td>
                    </tr>
                    </tr>
                    <tr>
                        <th>Tanggal Lomba</th>
                    <tr>
                        <th>Tanggal Lomba</th>
                        <td>{{ \Carbon\Carbon::parse($prestasi->lomba->tanggal_mulai)->format('d/m/Y') }} s.d.
                            {{ \Carbon\Carbon::parse($prestasi->lomba->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <th>Link Registrasi</th>
                        <td><a href="{{ $prestasi->lomba->link_registrasi }}"
                                target="_blank">{{ $prestasi->lomba->link_registrasi }}</a></td>
                    </tr>
                </table>

                <h5 class="mt-4">Daftar Mahasiswa</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prestasi->detailPrestasi as $detail)
                            <tr>
                                <td>{{ $detail->mahasiswa->nama_lengkap }}</td>
                                <td>{{ $detail->mahasiswa->nim }}</td>
                                <td>{{ $detail->mahasiswa->programStudi->nama_prodi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
                <a href="{{ url('/prestasi/' . $prestasi->id . '/edit_ajax') }}" class="btn btn-warning btn">Edit</a>
                <a href="{{ url('/prestasi/' . $prestasi->id . '/delete_ajax') }}" class="btn btn-danger btn">Hapus</a>

            </div>
        </div>
    </div>
@endempty