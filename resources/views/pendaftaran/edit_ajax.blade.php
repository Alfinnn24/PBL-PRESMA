@empty($pendaftaran)
    <div id="modal-master" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    Data pendaftaran tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/pendaftaran/' . $pendaftaran->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')

        <div id="modal-master" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Status Pendaftaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="{{ $pendaftaran->mahasiswa->nama_lengkap ?? '-' }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Lomba</label>
                        <input type="text" class="form-control" value="{{ $pendaftaran->lomba->nama ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Status Pendaftaran</label>
                        <select name="status" id="status" class="form-control" required>
                            @foreach ($status as $s)
                                <option value="{{ $s }}" @selected($pendaftaran->status == $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        <small id="error-status" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Hasil Lomba</label>
                        <input type="text" name="hasil" id="hasil" class="form-control" value="{{ $pendaftaran->hasil }}"
                            placeholder="Juara 1, Finalis, dll.">
                        <small id="error-hasil" class="error-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-edit").validate({
                rules: {
                    status: { required: true },
                    hasil: { maxlength: 255 }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (res) {
                            $('#myModal').modal('hide');
                            if (res.status) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message });
                                dataPendaftaran.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                if (res.msgField) {
                                    $.each(res.msgField, function (key, val) {
                                        $('#error-' + key).text(val[0]);
                                    });
                                }
                                Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
                            }
                        },
                        error: function () {
                            $('#myModal').modal('hide');
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat mengirim data.' });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty