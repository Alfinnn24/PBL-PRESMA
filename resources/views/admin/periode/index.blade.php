@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('periode/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Periode</button>
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
                        <div class="col-3">
                            <select class="form-control" id="nama" name="nama" required>
                                <option value="">- Semua -</option>
                                @foreach ($nama as $item)
                                    <option value="{{ $item->nama }}">{{ $item->nama}}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Periode</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_periode">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Periode</th>
                        <th>Tahun Periode</th>
                        <th>Semester Periode</th>
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
        var dataPeriode;
        $(document).ready(function() {
            var dataPeriode = $('#table_periode').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('periode/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    data: function(d) {
                        d.nama = $('#nama').val(); // kirim nilai dropdown ke server
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
                        data: "nama",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "tahun",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "semester",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#nama').on('change', function() {
                dataPeriode.ajax.reload(); // reload datatable ketika filter berubah
            });

        });
    </script>
@endpush