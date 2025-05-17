@empty($programStudi)
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
            <a href="{{ url('/program_studi') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/program_studi/' . $programStudi->id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Data Program Studi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Program Studi</label>
                    <input value="{{ $programStudi->nama_prodi }}" type="text" name="nama_prodi" id="nama_prodi" class="form-control" required>
                    <small id="error-nama_prodi" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kode Program Studi</label>
                    <input value="{{ $programStudi->kode_prodi }}" type="text" name="kode_prodi" id="kode_prodi" class="form-control" required>
                    <small id="error-kode_prodi" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jenjang Program Studi</label>
                    <select name="jenjang" id="jenjang" class="form-control" required>
                        <option value="">- Pilih Jenjang -</option>
                        <option value="D3" @selected($programStudi->jenjang == 'D3')>D3</option>
                        <option value="D4" @selected($programStudi->jenjang == 'D4')>D4</option>
                    </select>
                    <small id="error-jenjang" class="error-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                nama_prodi: { required: true, minlength: 3, maxlength: 100 },
                kode_prodi: { required: true, minlength: 3, maxlength: 10 },
                jenjang: { required: true },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: "POST",
                    data: $(form).serialize(),
                    success: function(response) {
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan: ' + xhr.responseText
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty
