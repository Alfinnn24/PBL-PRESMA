@empty($pendaftaran || !$pendaftaran->lomba)
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
                    Data yang anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/admin/pendaftaran_lomba') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    @php $lomba = $pendaftaran->lomba; @endphp
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran dan Lomba</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Informasi Mahasiswa</h5>
                <table class="table table-bordered mb-4">
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <td>{{ $pendaftaran->mahasiswa->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td>{{ $pendaftaran->mahasiswa->nim ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status Pendaftaran</th>
                        <td>{{ ucfirst($pendaftaran->status) }}</td>
                    </tr>
                    <tr>
                        <th>Hasil Seleksi</th>
                        <td>{{ $pendaftaran->hasil ?? 'Belum ada hasil' }}</td>
                    </tr>
                </table>

                <h5>Informasi Lomba</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>ID Lomba</th>
                        <td>{{ $lomba->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lomba</th>
                        <td>{{ $lomba->nama }}</td>
                    </tr>
                    <tr>
                        <th>Penyelenggara</th>
                        <td>{{ $lomba->penyelenggara }}</td>
                    </tr>
                    <tr>
                        <th>Tingkat</th>
                        <td>{{ $lomba->tingkat }}</td>
                    </tr>
                    <tr>
                        <th>Bidang Keahlian</th>
                        <td>{{ $lomba->bidangKeahlian->keahlian ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Persyaratan</th>
                        <td>{{ $lomba->persyaratan }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Peserta</th>
                        <td>{{ $lomba->jumlah_peserta }}</td>
                    </tr>
                    <tr>
                        <th>Link Registrasi</th>
                        <td>
                            @if ($lomba->link_registrasi)
                                <a href="{{ $lomba->link_registrasi }}" target="_blank" rel="noopener noreferrer">
                                    {{ $lomba->link_registrasi }}
                                </a>
                            @else
                                <span class="text-muted">Tidak tersedia</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ \Carbon\Carbon::parse($lomba->tanggal_mulai)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ \Carbon\Carbon::parse($lomba->tanggal_selesai)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td>{{ $lomba->periode->display_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pembuat</th>
                        <td>{{ $lomba->creator->full_name ?? 'Tidak Diketahui' }}</td>
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