<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Role Pengguna</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">- Pilih Role -</option>
                        @foreach ($role as $item)
                            <option value="{{ $item->role }}">{{ $item->role }}</option>
                        @endforeach
                    </select>
                    <small id="error-role" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label id="label-username">Username</label>
                    <input value="" type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input value="" type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input value="" type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control">
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div id="mahasiswa-fields" style="display: none;">
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" name="angkatan" id="angkatan" class="form-control">
                        <small id="error-angkatan" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control">
                        <small id="error-no_telp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control"></textarea>
                        <small id="error-alamat" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select name="program_studi_id" id="program_studi" class="form-control">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach ($programStudi as $ps)
                                <option value="{{ $ps->id }}">{{ $ps->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <small id="error-program_studi" class="error-text form-text text-danger"></small>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        var modal = document.getElementsByClassName("modal")[0];
        var role = modal.querySelector("#role");
        role.addEventListener("change", function () {
            const selectedRole = $(this).val().toLowerCase();
            if (selectedRole === 'mahasiswa') {
                $('#label-username').text('NIM');
                $('#mahasiswa-fields').show();
            } else if (selectedRole === 'dosen') {
                $('#label-username').text('NIDN');
                $('#mahasiswa-fields').hide();
            } else {
                $('#label-username').text('Username');
                $('#mahasiswa-fields').hide();
            }
        });

        $("#form-tambah").validate({
            rules: {
                role: {
                    required: true,
                },
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                email: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                nama_lengkap: {
                    required: true,
                },
                angkatan: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    }
                },
                no_telp: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    }
                },
                alamat: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    }
                },
                program_studi: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    }
                },
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
            },
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
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>