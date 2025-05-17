<form action="{{ url('/program_studi/store_ajax') }}" method="POST" id="form-tambah">
    @csrf

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Program Studi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Program Studi</label>
                    <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" required>
                    <small id="error-nama_prodi" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kode Program Studi</label>
                    <input type="text" name="kode_prodi" id="kode_prodi" class="form-control" required>
                    <small id="error-kode_prodi" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jenjang Program Studi</label>
                    <select name="jenjang" id="jenjang" class="form-control" required>
                        <option value="">- Pilih Jenjang -</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                    </select>
                    <small id="error-jenjang" class="error-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                nama_prodi: { required: true, minlength: 3, maxlength: 100 },
                kode_prodi: { required: true, minlength: 3, maxlength: 10 },
                jenjang: { required: true },
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
                            if (typeof dataProgramStudi !== 'undefined') {
                                dataProgramStudi.ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            if (response.msgField) {
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            }
                            if (response.message) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error("AJAX Error:", xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat mengirimkan data. Silakan coba lagi.'
                        });
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
