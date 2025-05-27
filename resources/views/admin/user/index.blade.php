@extends('layouts.template')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah User
                </button>
            </div>
        </div> --}}
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
                            <label>Filter Role:</label>
                            <select class="form-control" id="role" name="role">
                                <option value="">- Semua -</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->role }}">{{ $item->role }}</option>
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
                    <button onclick="modalAction('{{ url('user/create_ajax') }}')"
                        class="btn btn-success btn-md d-flex align-items-center">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                </div>
            </div>

            {{-- tabel --}}
            <table class="table modern-table display nowrap" id="table_user" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
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

    {{-- css buat tabel, NOTE: CLASS TABLE JADI GINI "table modern-table display nowrap" --}}
    <link rel="stylesheet" href="{{ asset('css/table.css') }}"> 
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataUser;
        $(document).ready(function() {
            dataUser = $('#table_user').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    url: "{{ url('user/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.role = $('#role').val();
                    }
                },
                columns: [{
                        // nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    },
                    {
                        data: "username",
                        className: "",
                        orderable: true, // jika ingin kolom ini bisa diurutkan
                        searchable: true, // jika ingin kolom ini bisa dicari
                        width: "20%"
                    },
                    {
                        data: "email",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "30%"
                    },
                    {
                        // mengambil data level hasil dari ORM berelasi
                        data: "role",
                        className: "",
                        orderable: false,
                        searchable: false,
                        width: "20%"
                    },
                    {
                        data: "action",
                        className: "",
                        orderable: false,
                        searchable: false,
                        width: "10%",
                        className: "text-center"

                    }
                ],
                initComplete: function() {
                    // Hubungkan input pencarian custom
                    $('#customSearch').on('keyup', function() {
                        dataUser.search(this.value).draw();
                    });
                }
            });
            $('#table_user_wrapper').children().first().addClass('d-none'); // buat menyembunyikan search sama show entries default lte
            $('#role').on('change', function() {
                dataUser.ajax.reload();
            })
        });
    </script>
@endpush
