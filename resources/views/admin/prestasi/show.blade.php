@extends('layouts.template')

@section('title', $page->title)

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $page->title }}</h3>
                </div>
                <div class="card-body">
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
                            <td>{{ \Carbon\Carbon::parse($prestasi->lomba->tanggal_mulai)->format('d/m/Y') }} s.d.
                                {{ \Carbon\Carbon::parse($prestasi->lomba->tanggal_selesai)->format('d/m/Y') }}
                            </td>
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
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a href="{{ url('notifikasi') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ url('prestasi') }}" class="btn btn-primary">lihat semua table</a>
                    </div>
                    <div>
                        <button
                            onclick="ubahStatus({{ $prestasi->id }}, 'approve')"class="btn btn-success">Setujui</button>
                        <button onclick="ubahStatus({{ $prestasi->id }}, 'reject')" class="btn btn-danger">Tolak</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        function ubahStatus(id, aksi) {
            let url = `/prestasi/${id}/${aksi}_ajax`;
            let inputCatatan =
                `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (optional)"></textarea>`;
            let title = aksi === 'approve' ? 'Setujui Prestasi?' : 'Tolak Prestasi?';
            let text = "Tindakan ini akan mengubah status prestasi.";
            let icon = aksi === 'approve' ? 'success' : 'warning';
            let confirmButtonText = aksi === 'approve' ? 'Ya, Setujui!' : 'Ya, Tolak!';
            let confirmButtonColor = aksi === 'approve' ? '#28a745' : '#d33';

            // Jika status ditolak, buat textarea sebagai input wajib
            if (aksi === 'reject') {
                inputCatatan =
                    `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (wajib)" required></textarea>`;
            }

            $('#myModal').modal('hide');

            setTimeout(() => {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    html: inputCatatan,
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#6c757d',
                    cancelButtonText: 'Batal',
                    confirmButtonText: confirmButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let catatan = document.getElementById('catatan').value;
                        // Jika tidak ada catatan pada saat disetujui, beri kalimat default
                        if (aksi === 'approve' && !catatan) catatan = 'Tidak ada catatan';

                        $.post(url, {
                            _token: '{{ csrf_token() }}',
                            catatan: catatan // Kirim catatan ke server
                        }, function(res) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.success,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                "{{ url('notifikasi') }}"; // Redirect ke notifikasi
                            });
                            // Refresh tabel di halaman index
                        }).fail(function() {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat memproses data.', 'error');
                        });
                    }
                });
            }, 500);
        }
    </script>
@endpush
