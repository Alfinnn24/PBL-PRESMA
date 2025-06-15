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
                        <select name="program_studi_id" id="program_studi_id" class="form-control">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach ($programStudi as $ps)
                                <option value="{{ $ps->id }}">{{ $ps->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <small id="error-program_studi" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div id="dosen-fields" style="display: none;">
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp_dosen" id="no_telp_dosen" class="form-control">
                        <small id="error-no_telp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select name="program_studi_id_dosen" id="program_studi_id_dosen" class="form-control">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach ($programStudi as $ps)
                                <option value="{{ $ps->id }}">{{ $ps->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <small id="error-program_studi_id_dosen" class="error-text form-text text-danger"></small>
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
                $('#dosen-fields').hide();
            } else if (selectedRole === 'dosen') {
                $('#label-username').text('NIDN');
                $('#mahasiswa-fields').hide();
                $('#dosen-fields').show();
            } else {
                $('#label-username').text('Username');
                $('#mahasiswa-fields').hide();
                $('#dosen-fields').hide();
            }
        });

        $("#form-tambah").validate({
            rules: {
                role: { required: true },
                username: { required: true, minlength: 3, maxlength: 20 },
                email: { required: true, minlength: 3, maxlength: 100 },
                password: { required: true, minlength: 6, maxlength: 20 },
                nama_lengkap: { required: true },
                angkatan: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    },
                    digits: true,
                    minlength: 4,
                    maxlength: 4,
                    range: [1900, new Date().getFullYear()]
                },
                no_telp: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    },
                    digits: true
                },
                no_telp_dosen: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'dosen';
                    },
                    digits: true
                },
                alamat: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    }
                },
                program_studi_id: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'mahasiswa';
                    }
                },
                program_studi_id_dosen: {
                    required: function () {
                        return $('#role').val().toLowerCase() === 'dosen';
                    }
                }
            },
            messages: {
                role: "Role penggguna harus dipilih.",
                username: {
                    required: "Username/NIM/NIDN wajib diisi.",
                    minlength: "Username harus memiliki minimal 3 karakter.",
                    maxlength: "Username maksimal 20 karakter."
                },
                email: {
                    required: "Email wajib diisi.",
                    minlength: "Email harus memiliki minimal 3 karakter.",
                    maxlength: "Email maksimal 100 karakter."
                },
                password: {
                    required: "Password wajib diisi.",
                    minlength: "Password harus memiliki minimal 6 karakter.",
                    maxlength: "Password maksimal 20 karakter."
                },
                nama_lengkap: "Nama lengkap wajib diisi.",
                angkatan: {
                    required: "Angkatan wajib diisi untuk mahasiswa.",
                    digits: "Angkatan harus berupa angka.",
                    minlength: "Angkatan harus terdiri dari 4 digit.",
                    maxlength: "Angkatan harus terdiri dari 4 digit.",
                    range: "Masukkan tahun yang valid antara 1900 dan " + new Date().getFullYear() + "."
                },
                no_telp: {
                    required: "No. Telepon wajib diisi.",
                    digits: "No. Telepon harus berupa angka."
                },
                alamat: "Alamat wajib diisi untuk mahasiswa.",
                program_studi_id: "Program Studi wajib dipilih untuk mahasiswa.",
                program_studi_id_dosen: "Program Studi wajib dipilih untuk dosen.",
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