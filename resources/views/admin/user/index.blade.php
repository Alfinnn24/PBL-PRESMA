@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('user/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah User
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
            {{-- filter opsi --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="role" name="role" required>
                                <option value="">- Semua -</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->role }}">{{ $item->role }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Role Pengguna</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm display nowrap" id="table_user"
                style="width:100%">
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
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
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
                        searchable: false
                    },
                    {
                        data: "username",
                        className: "",
                        orderable: true, // jika ingin kolom ini bisa diurutkan
                        searchable: true // jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "email",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        // mengambil data level hasil dari ORM berelasi
                        data: "role",
                        className: "",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "action",
                        className: "",
                        orderable: false,
                        searchable: false,
                        width: "20%",
                        className: "text-center"

                    }
                ]
            });

            $('#role').on('change', function() {
                dataUser.ajax.reload();
            })
        });
    </script>
@endpush
