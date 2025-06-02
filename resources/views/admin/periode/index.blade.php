@extends('layouts.template')

@section('content')
    <div class="card">
        <!-- <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('periode/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Periode</button>
            </div>
        </div> -->
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            {{-- filternya tinggal dicomot terus disesuaikan --}}
            {{-- bagian kiri filter --}}
            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <label>Filter Periode:</label>
                            <select class="form-control" id="nama" name="nama">
                                <option value="">- Semua -</option>
                                @foreach ($nama as $item)
                                    <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label>Pencarian:</label>
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Cari..." id="customSearch">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- bagian kanan tambah -->
                <div class="col-md-4 d-flex align-items-center justify-content-end mt-4">
                    <button onclick="modalAction('{{ url('periode/create_ajax') }}')"
                        class="btn btn-success btn-md d-flex align-items-center">
                        <i class="fas fa-plus"></i> Tambah Periode
                    </button>
                </div>
            </div>

            {{-- tabel --}}
            <table class="table modern-table display nowrap" id="table_periode" style="width:100%">
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
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush

@push('js')
    <script>
        // Inisialisasi CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataPeriode;
        $(document).ready(function() {
            dataPeriode = $('#table_periode').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('periode/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.nama = $('#nama').val();
                    }
                },
                columns: [{
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
                ],
                initComplete: function() {
                    // Hubungkan input pencarian custom
                    $('#customSearch').on('keyup', function() {
                        dataPeriode.search(this.value).draw();
                    });
                }
            });

            $('#table_periode_wrapper').children().first().addClass('d-none'); // buat menyembunyikan search sama show entries default lte
            $('#nama').on('change', function() {
                dataPeriode.ajax.reload();
            });

        });
    </script>
@endpush
