@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/bobot') }}')" class="btn btn-sm btn-success mt-1">
                    Atur Bobot Sistem Rekomendasi
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Status</label>
                    <select id="filter_status" class="form-control">
                        <option value="">- Semua -</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <!-- <div class="col-md-3">
                                                            <label>Kecocokan</label>
                                                            <select id="filter_kecocokan" class="form-control">
                                                                <option value="">- Semua -</option>
                                                                <option value="tinggi">Sangat direkomendasikan</option>
                                                                <option value="sedang">Direkomendasikan</option>
                                                                <option value="rendah">Cukup direkomendasikan</option>
                                                                <option value="srendah">Tidak direkomendasikan</option>
                                                            </select>
                                                        </div> -->
            </div>

            <table class="table modern-table display nowrap" id="table_rekomendasi" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Lomba</th>
                        <th>Mahasiswa</th>
                        <th>Status</th>
                        <th>Hasil Rekomendasi</th>
                        <th>Dosen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
            <div id="pagination" class="d-flex justify-content-end mt-2"></div>
        </div>
    </div>

    {{-- Modal AJAX --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').modal('hide').removeData('bs.modal');
            $('#myModal').html('');
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var tableRekomendasi;

        $(document).ready(function () {
            tableRekomendasi = $('#table_rekomendasi').DataTable({
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ url('rekomendasi/list-all') }}",
                    type: "GET",
                    data: function (d) {
                        d.status = $('#filter_status').val();
                        d.kecocokan = $('#filter_kecocokan').val();
                    }
                },
                columns: [
                    { data: 'lomba', name: 'lomba' },
                    { data: 'mahasiswa', name: 'mahasiswa' },
                    { data: 'status', name: 'status' },
                    { data: 'hasil_rekomendasi', name: 'hasil_rekomendasi' },
                    { data: 'dosen', name: 'dosen' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ],
                // hapus createdRow yang rowspan
                rowGroup: {
                    dataSrc: 'lomba',  // grup berdasarkan kolom lomba
                    // Optional: kamu bisa custom tampilkan header grupnya
                    startRender: function (rows, group) {
                        return group + ' (' + rows.count() + ' mahasiswa)';
                    }
                }
            });

            $('#filter_status, #filter_kecocokan').on('change', function () {
                tableRekomendasi.ajax.reload();
            });
        });

        function ubahStatus(id, aksi) {
            let url = `/rekomendasi/${id}/${aksi}_ajax`;
            let title = aksi === 'approve' ? 'Setujui Rekomendasi?' : 'Tolak Rekomendasi?';
            let icon = aksi === 'approve' ? 'success' : 'warning';
            let confirmText = aksi === 'approve' ? 'Ya, Setujui!' : 'Ya, Tolak!';
            // let inputCatatan = aksi === 'reject'
            //     ? `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (wajib)" required></textarea>`
            //     : `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (optional)"></textarea>`;

            $('#myModal').modal('hide');

            setTimeout(() => {
                Swal.fire({
                    title: title,
                    icon: icon,
                    //html: inputCatatan,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: aksi === 'approve' ? '#28a745' : '#d33'
                }).then(result => {
                    if (result.isConfirmed) {
                        //let catatan = document.getElementById('catatan').value;
                        //if (aksi === 'approve' && !catatan) catatan = 'Tidak ada catatan';

                        $.post(url, {
                            _token: '{{ csrf_token() }}'
                        }, function (res) {
                            Swal.fire('Berhasil', res.message, 'success');
                            tableRekomendasi.ajax.reload(null, false);
                        }).fail(function () {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat memproses data.', 'error');
                        });

                    }
                });
            }, 500);
        }
        function konfirmasiProsesTopsis() {
            Swal.fire({
                title: 'Yakin?',
                text: 'Proses ini akan mengganti semua status lomba yang telah disetujui menjadi Pending. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, proses sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/tes-rekomendasi/topsis',
                        type: 'GET',
                        beforeSend: function () {
                            Swal.showLoading();  // Tampilkan loading spinner
                        },
                        success: function (response) {
                            Swal.fire(
                                'Sukses!',
                                'Proses TOPSIS selesai dan data berhasil diperbarui.',
                                'success'
                            ).then(() => {
                                window.location.reload();  // Reload halaman setelah OK
                            });
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat memproses data.',
                                'error'
                            );
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Dibatalkan',
                        'Proses dibatalkan.',
                        'info'
                    );
                }
            });
        }


    </script>
@endpush