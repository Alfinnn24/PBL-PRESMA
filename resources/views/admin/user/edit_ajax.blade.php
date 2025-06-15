@empty($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/user/' . $user->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Data umum --}}
                    <div class="form-group">
                        <label>Role Pengguna</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <small id="error-role" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input value="{{ $user->username }}" type="text" name="username" id="username" class="form-control"
                            required @if($user->role === 'dosen' || $user->role === 'mahasiswa') pattern="\d*"
                            inputmode="numeric" @endif>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="{{ $user->email }}" type="text" name="email" id="email" class="form-control" required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small class="form-text text-muted">Abaikan jika tidak ingin ubah password</small>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>

                    {{-- Detail berdasarkan role --}}
                    @if ($user->role === 'mahasiswa' && $detail)
                        <hr>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input value="{{ $detail->nama_lengkap }}" type="text" name="nama_lengkap" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>NIM</label>
                            <input value="{{ $detail->nim }}" type="text" name="nim" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Angkatan</label>
                            <input value="{{ $detail->angkatan }}" type="text" name="angkatan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input value="{{ $detail->no_telp }}" type="text" name="no_telp" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input value="{{ $detail->alamat }}" type="text" name="alamat" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Program Studi</label>
                            <select name="program_studi_id" class="form-control">
                                <option value="">- Pilih Program Studi -</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}" {{ $detail->program_studi_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-program_studi_id" class="error-text form-text text-danger"></small>
                        </div>
                    @elseif ($user->role === 'dosen' && $detail)
                        <hr>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input value="{{ $detail->nama_lengkap }}" type="text" name="nama_lengkap" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>NIDN</label>
                            <input value="{{ $detail->nidn }}" type="text" name="nidn" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input value="{{ $detail->no_telp }}" type="text" name="no_telp_dosen" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Program Studi</label>
                            <select name="program_studi_id_dosen" class="form-control">
                                <option value="">- Pilih Program Studi -</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}" {{ $detail->program_studi_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-program_studi_id_dosen" class="error-text form-text text-danger"></small>
                        </div>
                    @elseif ($user->role === 'admin' && $detail)
                        <hr>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input value="{{ $detail->nama_lengkap }}" type="text" name="nama_lengkap" class="form-control">
                        </div>
                    @endif

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
            $("#form-edit").validate({
                rules: {
                    role: {
                        required: true
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
                        minlength: 6,
                        maxlength: 20
                    },
                    nama_lengkap: {
                        required: true
                    },
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
                        required: "No. Telepon wajib diisi untuk mahasiswa.",
                        digits: "No. Telepon harus berupa angka."
                    },
                    no_telp_dosen: {
                        required: "No. Telepon wajib diisi untuk dosen.",
                        digits: "No. Telepon harus berupa angka."
                    },
                    alamat: "Alamat wajib diisi untuk mahasiswa.",
                    program_studi_id: "Program Studi wajib dipilih untuk mahasiswa.",
                    program_studi_id_dosen: "Program Studi wajib dipilih untuk dosen."
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