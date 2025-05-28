@extends('layouts.template') {{-- Sesuaikan dengan layout AdminLTE Anda --}}

@section('title', $page->title)

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="table-notifikasi">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Pesan</th>
                                <th>Link</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(function() {
            $('#table-notifikasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('notifikasi.index') }}",
                columns: [{
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'pesan',
                        name: 'pesan'
                    },
                    {
                        data: 'link',
                        render: function(data, type, row) {
                            return `<a href="${row.link}" class="btn btn-sm ${row.read == '1' ? 'btn-primary' : 'btn-success'}">
                    ${row.linkTitle}
                    </a>`;
                        },
                        orderable: false,
                        searchable: false
                    }, 
                //     {
                //         data: 'id',
                //         render: function(data, type, row) {
                //             // Gunakan link dari data notifikasi
                //             return `<a href="${row.link}" class="btn btn-sm ${row.read == '1' ? 'btn-primary' : 'btn-success'}">
                // ${row.linkTitle}
                // </a>`;
                //         },
                //         orderable: false,
                //         searchable: false
                //     }
                ]
            });
        });
    </script>
@endpush
