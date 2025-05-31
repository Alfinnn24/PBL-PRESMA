@extends('layouts.template') {{-- Sesuaikan dengan layout AdminLTE Anda --}}

@section('title', $page->title)

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <table class="table modern-table display nowrap" id="table-notifikasi">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('css')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}

    {{-- css buat tabel, NOTE: CLASS TABLE JADI GINI "table modern-table display nowrap" --}}
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            const link =
                                "{{ route('notifikasi.read', ['notification_id' => ':id']) }}"
                                .replace(
                                    ':id', row.id);
                            return `<a href="${link}" class="btn btn-sm ${row.read == '1' ? 'btn-primary' : 'btn-success'}">
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
            $('#table-notifikasi_wrapper').children().first().addClass('d-none');
        });
    </script>
@endpush
