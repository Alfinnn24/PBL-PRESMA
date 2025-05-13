@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('prestasi/create_ajax') }}')"
                    class="btn btn-sm btn-success mt-1">Tambah Prestasi</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- filter opsi (jika nanti ingin filter berdasarkan nama prestasi, bisa diaktifkan) --}}
            {{--
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="nama_prestasi" name="nama_prestasi">
                                <option value="">- Semua -</option>
                                @foreach ($namaPrestasi as $item)
                                <option value="{{ $item->nama_prestasi }}">{{ $item->nama_prestasi }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Prestasi</small>
                        </div>
                    </div>
                </div>
            </div>
            --}}

            <table class="table table-bordered table-striped table-hover table-sm" id="table_prestasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Prestasi</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Jumlah Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true">
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var dataPrestasi;
        $(document).ready(function () {
            dataPrestasi = $('#table_prestasi').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('prestasi/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function (d) {
                        // contoh jika ada filter, tambahkan:
                        // d.nama_prestasi = $('#nama_prestasi').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_prestasi",
                    },
                    {
                        data: "status",
                    },
                    {
                        data: "catatan",
                    },
                    {
                        data: "jumlah_mahasiswa",
                        className: "text-center"
                    },
                    {
                        data: "action",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // jika pakai filter
            // $('#nama_prestasi').on('change', function () {
            //     dataPrestasi.ajax.reload();
            // });
        });

        function ubahStatus(id, aksi) {
            let url = `/prestasi/${id}/${aksi}_ajax`;
            let inputCatatan = `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (optional)"></textarea>`;
            let title = aksi === 'approve' ? 'Setujui Prestasi?' : 'Tolak Prestasi?';
            let text = "Tindakan ini akan mengubah status prestasi.";
            let icon = aksi === 'approve' ? 'success' : 'warning';
            let confirmButtonText = aksi === 'approve' ? 'Ya, Setujui!' : 'Ya, Tolak!';
            let confirmButtonColor = aksi === 'approve' ? '#28a745' : '#d33';

            // Jika status ditolak, buat textarea sebagai input wajib
            if (aksi === 'reject') {
                inputCatatan = `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (wajib)" required></textarea>`;
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
                        if (aksi === 'approve' && !catatan) {
                            catatan = 'Tidak ada catatan';
                        }

                        $.post(url, {
                            _token: '{{ csrf_token() }}',
                            catatan: catatan // Kirim catatan ke server
                        }, function (res) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.success,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Tutup modal setelah aksi selesai
                            $('#myModal').modal('hide');

                            // Refresh tabel di halaman index
                            if ($.fn.DataTable.isDataTable('#table_prestasi')) {
                                $('#table_prestasi').DataTable().ajax.reload(null, false); // reload tanpa reset halaman
                            }
                        }).fail(function (xhr) {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat memproses data.', 'error');
                            $('#myModal').modal('hide');
                        });
                    }
                });
            }, 500);
        }
    </script>
@endpush