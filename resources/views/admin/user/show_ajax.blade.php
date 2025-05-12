@empty($detail)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data detail tidak ditemukan
                </div>
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengguna - {{ ucfirst($user->role) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="25%">Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    @if($user->role === 'mahasiswa')
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $detail->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>{{ $detail->nim }}</td>
                        </tr>
                        <tr>
                            <th>Angkatan</th>
                            <td>{{ $detail->angkatan }}</td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td>{{ $detail->no_telp }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $detail->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Program Studi</th>
                            <td>{{ $detail->programStudi->nama_prodi ?? '-' }}</td>
                        </tr>

                    @elseif($user->role === 'dosen')
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $detail->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>NIDN</th>
                            <td>{{ $detail->nidn }}</td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td>{{ $detail->no_telp }}</td>
                        </tr>
                        <tr>
                            <th>Program Studi</th>
                            <td>{{ $detail->programStudi->nama_prodi ?? '-' }}</td>
                        </tr>

                    @elseif($user->role === 'admin')
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>{{ $detail->nama_lengkap }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th>Foto Profile</th>
                        <td>
                            @if($detail->foto_profile)
                                <img src="{{ asset('storage/' . $detail->foto_profile) }}" alt="Foto" width="100">
                            @else
                                <em>Tidak ada foto</em>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty