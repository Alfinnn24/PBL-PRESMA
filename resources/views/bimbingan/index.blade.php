@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa Bimbingan</h3>
            <div class="card-tools">
                {{-- Bisa tambahkan tombol tambah mahasiswa bimbingan di sini jika perlu --}}
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Filter pencarian nama atau NIM --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Cari nama atau NIM mahasiswa" />
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm display nowrap" id="table_mahasiswa_bimbingan" style="width:100%">
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

    {{-- Modal Bootstrap Lengkap --}}
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

        var tableMahasiswaBimbingan;
        $(document).ready(function() {
            tableMahasiswaBimbingan = $('#table_mahasiswa_bimbingan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('dosen/bimbingan/list') }}",
                    type: "POST",
                    data: function(d) {
                        d.search = $('#search').val();
                        d._token = "{{ csrf_token() }}";
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
                        data: "nim",
                        className: "text-center"
                    },
                    {
                        data: "nama_lengkap"
                    },
                    {
                        data: "prestasi_count",
                        className: "text-center"
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'asc']],
                lengthMenu: [10, 25, 50],
                responsive: true,
            });

            // Reload tabel saat pencarian berubah dengan delay
            let typingTimer;
            let doneTypingInterval = 500;
            $('#search').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    tableMahasiswaBimbingan.ajax.reload();
                }, doneTypingInterval);
            });

            $('#search').on('keydown', function () {
                clearTimeout(typingTimer);
            });
        });
    </script>
@endpush
