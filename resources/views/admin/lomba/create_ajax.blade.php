<form action="{{ url('/lomba/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Lomba</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lomba</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>

            <div class="form-group">
                <label>Penyelenggara</label>
                <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" required>
                <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Tingkat</label>
                <select name="tingkat" id="tingkat" class="form-control" required>
                    <option value="">- Pilih Tingkat Lomba -</option>
                @foreach($tingkat_lomba as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
                </select>
                <small id="error-tingkat" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Bidang Keahlian</label>
                <select name="bidang_keahlian_id" id="bidang_keahlian_id" class="form-control" required>
                    <option value="">- Pilih Keahlian -</option>
                    @foreach($bidang_keahlian as $item)
                        <option value="{{ $item->id }}">{{ $item->keahlian }}</option>
                    @endforeach
                </select>
                <small id="error-bidang_keahlian_id" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Persyaratan</label>
                    <textarea name="persyaratan" id="persyaratan" class="form-control" required></textarea>
                <small id="error-persyaratan" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Jumlah Peserta</label>
                    <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" required>
                <small id="error-jumlah_peserta" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Link Registrasi</label>
                    <input type="url" name="link_registrasi" id="link_registrasi" class="form-control" required>
                <small id="error-link_registrasi" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label>Periode</label>
                <select name="periode_id" id="periode_id" class="form-control" required>
                    <option value="">- Pilih Periode -</option>
                    @foreach($periode as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
                <small id="error-periode_id" class="error-text form-text text-danger"></small>
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
              nama: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
              penyelenggara: {
                required: true,
                maxlength: 255
            },
              tingkat: {
                required: true,
                maxlength: 50
            },
              bidang_keahlian_id: {
                required: true
            },
              persyaratan: {
                maxlength: 500
            },
              jumlah_peserta: {
                digits: true,
                min: 1
            },
              link_registrasi: {
                url: true,
                maxlength: 255
            },
              tanggal_mulai: {
                required: true,
                date: true
            },
              tanggal_selesai: {
                required: true,
                date: true
            },
              periode_id: {
                required: true
            }
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
                            dataLomba.ajax.reload();
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