@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('program_studi/create_ajax') }}')"
                    class="btn btn-sm btn-success mt-1">Tambah Program Studi
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

            {{-- Filter Program Studi --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="nama" name="nama">
                                <option value="">- Semua -</option>
                                @foreach ($nama as $item)
                                    <option value="{{ $item->nama_prodi }}">{{ $item->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Program Studi</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm display nowrap"
                    id="table_program_studi" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Program Studi</th>
                            <th>Kode Program Studi</th>
                            <th>Jenjang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap Lengkap -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-body" id="modalBody">
                <!-- Konten AJAX akan dimuat di sini -->
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Fungsi AJAX untuk memuat konten ke dalam modal
        function modalAction(url = '') {
            $('#modalBody').html('<div class="text-center">Loading...</div>');
            $('#myModal').modal('show');

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    $('#modalBody').html(html);
                })
                .catch(error => {
                    $('#modalBody').html('<div class="text-danger">Gagal memuat data.</div>');
                    console.error(error);
                });
        }

        var dataProgramStudi; // global
        $(document).ready(function() {
            dataProgramStudi = $('#table_program_studi').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('program_studi/list') }}",
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
                        data: "nama_prodi",
                        className: "text-center"
                    },
                    {
                        data: "kode_prodi",
                        className: "text-center"
                    },
                    {
                        data: "jenjang",
                        className: "text-center"
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload tabel saat filter berubah
            $('#nama').on('change', function() {
                dataProgramStudi.ajax.reload();
            });
        });
    </script>
@endpush
