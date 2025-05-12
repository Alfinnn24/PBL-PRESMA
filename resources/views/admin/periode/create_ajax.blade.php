<form action="{{ url('/periode/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Periode</label>
                    <input value="" type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tahun Periode</label>
                    <input value="" type="number" name="tahun" id="tahun" class="form-control" required>
                    <small id="error-tahun" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Semester Periode</label>
                    <select name="semester" id="semester" class="form-control" required>
                        <option value="">- Pilih Semester -</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                    <small id="error-semester" class="error-text form-text text-danger"></small>
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
        $("#form-tambah").validate({
            rules: {
                nama: { required: true, minlength: 3, maxlength: 20 },
                tahun: { required: true, digits: true, minlength: 4, maxlength: 4 },
                semester: { required: true },
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
                            dataPeriode.ajax.reload();
                        } else {
                            $('.error-text').text('');
    
                            if (response.msgField) {
                                $.each(response.msgField, function (prefix, val) {
                                   $('#error-' + prefix).text(val[0]);
                                });
                            }

                            // Tampilkan alert umum
                            if (response.message) {
                                Swal.fire({
                                   icon: 'error',
                                   title: 'Gagal',
                                   text: response.message
                                });
                            }
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);  // Menambahkan error handling
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
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>