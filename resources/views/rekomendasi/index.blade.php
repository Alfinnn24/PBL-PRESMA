@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('lomba/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Lomba
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
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
                        <th>No</th>
                        <th>Lomba</th>
                        <th>Status</th>
                        <th>Skor</th>
                        <th>Hasil Rekomendasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
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
                serverSide: false,
                ajax: {
                    url: "{{ url('rekomendasi/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function (d) {
                        d.status = $('#filter_status').val();
                        d.kecocokan = $('#filter_kecocokan').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    className: "text-center"
                },
                {
                    data: 'lomba'
                },
                {
                    data: 'status',
                    className: "text-capitalize"
                },
                {
                    data: 'skor',
                    className: "text-center"
                },
                {
                    data: 'hasil_rekomendasi'
                },
                {
                    data: 'aksi',
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
                ]
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

        function buatPendaftaran(lombaId) {
            Swal.fire({
                title: 'Daftar ke Lomba?',
                text: 'Formulir akan dibuka dalam modal.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    modalAction(`/pendaftaran/create_ajax?lomba_id=${lombaId}`);
                }
            });
        }

    </script>
@endpush