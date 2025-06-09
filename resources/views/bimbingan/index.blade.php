@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa Bimbingan</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- PENCARIAN CUSTOM (Optional – Bisa dikembangkan lagi nanti) --}}
            {{-- <div class="row mb-3">
                <div class="col-md-6">
                    <label>Cari Mahasiswa:</label>
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="Ketik nama/NIM..." id="customSearch">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- TABEL MAHASISWA BIMBINGAN --}}
            <div style="overflow-x:auto;">
                <table class="table modern-table display nowrap" id="table_mahasiswa_bimbingan" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Jumlah Prestasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-body" id="modalBody">
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush

@push('js')
    <script>
        // Fungsi AJAX untuk membuka modal detail prestasi
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

        $(document).ready(function () {
            let table = $('#table_mahasiswa_bimbingan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('dosen/bimbingan/list') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "nim", className: "text-center" },
                    { data: "nama_lengkap" },
                    { data: "prestasi_count", className: "text-center", searchable: false },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ],
                order: [[1, 'asc']],
                lengthMenu: [10, 25, 50],
                responsive: true,
                language: {
                    search: "Cari Mahasiswa:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)"
                }
            });

            // Uncomment jika pakai customSearch
            // $('#customSearch').on('keyup', function () {
            //     table.search(this.value).draw();
            // });

            // Hilangkan tampilan filter default (opsional)
            // $('#table_mahasiswa_bimbingan_wrapper').children().first().addClass('d-none');
        });
    </script>
@endpush