@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button class="btn btn-sm btn-outline-primary" onclick="switchTable('pendaftaran')">History Pendaftaran
                    Lomba</button>
                <button class="btn btn-sm btn-outline-success" onclick="switchTable('tambahan')">History Data Lomba</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div id="table_pendaftaran_container" style="display: block;">
                <table class="table modern-table display nowrap" id="table_pendaftaran" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lomba</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div id="table_tambahan_container" style="display: none;">
                <table class="table modern-table display nowrap" id="table_tambahan" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode Lomba</th>
                            <th>Nama Lomba</th>
                            <th>Penyelenggara Lomba</th>
                            <th>Tingkat Lomba</th>
                            <th>Bidang Keahlian Lomba</th>
                            <th>Status Lomba</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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

        let tablePendaftaran, tableTambahan;

        function switchTable(type) {
            if (type === 'pendaftaran') {
                $('#table_pendaftaran_container').show();
                $('#table_tambahan_container').hide();
                if (!tablePendaftaran) initTablePendaftaran();
            } else {
                $('#table_pendaftaran_container').hide();
                $('#table_tambahan_container').show();
                if (!tableTambahan) initTableTambahan();
            }
        }

        function initTablePendaftaran() {
            tablePendaftaran = $('#table_pendaftaran').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('pendaftaran/list') }}",
                    type: "POST",
                    data: { _token: '{{ csrf_token() }}' }
                },
                columns: [
                    { data: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                    { data: 'nama_lomba' },
                    { data: 'tanggal_daftar', className: "text-center" },
                    { data: 'status', className: "text-capitalize text-center" },
                    { data: 'aksi', orderable: false, searchable: false, className: "text-center" }
                ]

            });
        }

        function initTableTambahan() {
            tableTambahan = $('#table_tambahan').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('dosen/lomba/list') }}",
                    type: "POST",
                    data: { _token: '{{ csrf_token() }}' }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    width: "40px"
                },
                {
                    data: "periode_display_name",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    width: "150px"
                },
                {
                    data: "nama",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    width: "150px"
                },
                {
                    data: "penyelenggara",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    width: "200px"
                },
                {
                    data: "tingkat",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    width: "150px"
                },
                {
                    data: "keahlian",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    width: "150px"
                },
                {
                    data: "is_verified",
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    width: "150px"
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    width: "200px"
                }
                ],
            });
        }

        $(document).ready(function () {
            switchTable('pendaftaran');
        });
    </script>
@endpush