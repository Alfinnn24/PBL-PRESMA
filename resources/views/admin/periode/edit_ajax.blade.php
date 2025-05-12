@empty($periode)
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
            <a href="{{ url('/periode') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/periode/' . $periode->id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Periode</label>
                    <input value="{{ $periode->nama }}" type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tahun Periode</label>
                    <input value="{{ $periode->tahun }}" type="number" name="tahun" id="tahun" class="form-control" required>
                    <small id="error-tahun" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Semester Periode</label>
                    <select name="semester" id="semester" class="form-control" required>
                        <option value="">- Pilih Semester -</option>
                        <option value="Ganjil" @selected($periode->semester == 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected($periode->semester == 'Genap')>Genap</option>
                    </select>
                    <small id="error-semester" class="error-text text-danger"></small>
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
$(document).ready(function() {
    $("#form-edit").validate({
        rules: {
            nama: { required: true, minlength: 3, maxlength: 20 },
            tahun: { required: true, digits: true, minlength: 4, maxlength: 4 },
            semester: { required: true },
        },
        submitHandler: function(form) {
            console.log("Data sebelum dikirim:", $(form).serialize());

            $.ajax({
                url: form.action,
                type: "POST",
                data: $(form).serialize(),
                success: function(response) {
                    console.log("Response dari server:", response);

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
                        // Cek apakah ada validasi error (msgField)
                        if (response.msgField) {
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }

                        // Tampilkan alert umum jika ada error
                        if (response.message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                }
            },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
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