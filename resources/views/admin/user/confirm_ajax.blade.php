@empty($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/user/' . $user->id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                        Apakah Anda yakin ingin menghapus data berikut ini?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-4">Role Pengguna:</th>
                            <td class="col-8">{{ $user->role }}</td>
                        </tr>
                        <tr>
                            <th class="text-right">Username:</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th class="text-right">Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>

                        @if ($detail)
                            <tr>
                                <th class="text-right">Nama Lengkap:</th>
                                <td>{{ $detail->nama_lengkap ?? '-' }}</td>
                            </tr>

                            @if ($user->role === 'mahasiswa')
                                <tr>
                                    <th class="text-right">NIM:</th>
                                    <td>{{ $detail->nim }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Angkatan:</th>
                                    <td>{{ $detail->angkatan }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">No. Telp:</th>
                                    <td>{{ $detail->no_telp }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Alamat:</th>
                                    <td>{{ $detail->alamat }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Program Studi:</th>
                                    <td>{{ optional($prodi->find($detail->program_studi_id))->nama_prodi ?? '-' }}</td>
                                </tr>
                            @elseif ($user->role === 'dosen')
                                <tr>
                                    <th class="text-right">NIDN:</th>
                                    <td>{{ $detail->nidn }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">No. Telp:</th>
                                    <td>{{ $detail->no_telp }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Program Studi:</th>
                                    <td>{{ optional($prodi->find($detail->program_studi_id))->nama_prodi ?? '-' }}</td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-delete").validate({
                rules: {},
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataUser.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField ?? {}, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty