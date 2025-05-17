@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('lomba/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Lomba</button>
            </div>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            {{-- filter opsi --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>

            {{-- Filter Periode --}}
            <div class="col-3">
                <select class="form-control" id="nama" name="nama">
                    <option value="">- Semua -</option>
                    @foreach ($periode as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Nama Periode</small>
            </div>

            {{-- Filter Bidang Keahlian --}}
            <div class="col-3">
                <select class="form-control" id="bidang_keahlian" name="bidang_keahlian">
                    <option value="">- Semua -</option>
                    @foreach ($bidang_keahlian as $item)
                        <option value="{{ $item->id }}">{{ $item->keahlian }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Bidang Keahlian</small>
            </div>

            {{-- Filter User --}}
            <div class="col-3">
                <select class="form-control" id="user" name="user">
                    <option value="">- Semua -</option>
                    @foreach ($user as $item)
                        <option value="{{ $item->id }}">{{ $item->username }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Created By</small>
            </div>
        </div>
    </div>
</div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_lomba">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lomba</th>
                        <th>Penyelenggara Lomba</th>
                        <th>Tingkat Lomba</th>
                        <th>Bidang Keahlian Lomba</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
        function modalAction(url = ''){ 
            $('#myModal').load(url,function(){ 
                $('#myModal').modal('show'); 
            }); 
        } 
        var dataLomba;
        $(document).ready(function() {
            var dataLomba = $('#table_lomba').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('lomba/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    data: function(d) {
                       d.nama = $('#nama').val(); // nama periode
                       d.bidang_keahlian = $('#bidang_keahlian').val(); // bidang keahlian
                       d.user = $('#user').val(); // user (created_by)
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "40px"
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
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "180px"
                    }
                ]

            });

            $('#nama, #bidang_keahlian, #user').on('change', function() {
                dataLomba.ajax.reload(); // reload datatable ketika filter berubah
            });

        });
    </script>
@endpush