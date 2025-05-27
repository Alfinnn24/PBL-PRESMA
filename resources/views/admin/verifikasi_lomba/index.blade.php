@extends('layouts.template')

@section('content')
    <div class="card">
        <!-- <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('lomba/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Lomba</button>
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
                            <label>Filter Bidang Keahlian:</label>
                            <select class="form-control" id="bidang_keahlian" name="bidang_keahlian">
                                <option value="">- Semua -</option>
                                @foreach ($bidang_keahlian as $item)
                                    <option value="{{ $item->id }}">{{ $item->keahlian }}</option>
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
            </div>
            <div style="overflow-x:auto;">
                <table class="table modern-table display nowrap" id="table_lomba" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode Lomba</th>
                            <th>Nama Lomba</th>
                            <th>Penyelenggara Lomba</th>
                            <th>Tingkat Lomba</th>
                            <th>Bidang Keahlian Lomba</th>
                            <th>Status Lomba</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataLomba;
        $(document).ready(function() {
            dataLomba = $('#table_lomba').DataTable({
                //scrollX: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('verifikasi_lomba/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.bidang_keahlian = $('#bidang_keahlian').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "40px"
                    },
                    {
                        data: "periode_id",
                        className: "text-center",
                        orderable: true,
                        searchable: true,
                        width: "150px"
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
                        data: "is_verified",
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
                        width: "200px"
                    }
                ],
                initComplete: function() {
                    // Hubungkan input pencarian custom
                    $('#customSearch').on('keyup', function() {
                        dataLomba.search(this.value).draw();
                    });
                }
            });

            $('#table_lomba_wrapper').children().first().addClass('d-none'); // buat menyembunyikan search sama show entries default lte
            $('#bidang_keahlian').on('change', function() {
                dataLomba.ajax.reload();
            });

        });

        function ubahStatus(id, aksi) {
            const url = `{{ url('verifikasi_lomba') }}/${id}/${aksi}`;
            const title = aksi === 'approve' ? 'Setujui Lomba?' : 'Tolak Lomba?';
            const icon = aksi === 'approve' ? 'success' : 'warning';
            const confirmText = aksi === 'approve' ? 'Ya, Setujui!' : 'Ya, Tolak!';

            // Tutup modal jika sedang terbuka
            $('#myModal').modal('hide');

            setTimeout(() => {
                Swal.fire({
                    title: title,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: aksi === 'approve' ? '#28a745' : '#d33'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.post(url, {
                            _token: '{{ csrf_token() }}'
                        }, function(res) {
                            Swal.fire('Berhasil', res.message, 'success');
                            if (typeof tableLomba !== 'undefined') {
                                tableLomba.ajax.reload(null, false);
                            }
                        }).fail(function(xhr) {
                            let errorMsg = 'Terjadi kesalahan saat memproses data.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Gagal', errorMsg, 'error');
                        });
                    }
                });
            }, 500);
    }

    </script>
@endpush
