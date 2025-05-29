<form action="{{ url('/pendaftaran/ajax') }}" method="POST" id="form-tambah" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulir Pendaftaran Lomba</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Lomba</label>
                    <select name="lomba_id" id="lomba_id" class="form-control" required>
                        <option value="">- Pilih Lomba -</option>
                        @foreach($lomba as $l)
                            <option value="{{ $l->id }}" {{ (isset($lombaTerpilih) && $l->id == $lombaTerpilih) ? 'selected' : '' }}>
                                {{ $l->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-lomba_id" class="error-text form-text text-danger"></small>
                </div>

                <div id="info-lomba" class="mt-3">
                    <div class="form-group">
                        <label>Penyelenggara</label>
                        <input type="text" class="form-control" id="penyelenggara" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pelaksanaan</label>
                        <input type="date" class="form-control" id="tanggal_pelaksanaan" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tingkat Lomba</label>
                        <input type="text" class="form-control" id="tingkat" readonly>
                    </div>
                    <div class="form-group">
                        <label>Kategori / Bidang</label>
                        <input type="text" class="form-control" id="kategori" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mahasiswa</label>
                    <input type="text" name="mahasiswa_nim" id="mahasiswa_nim" class="form-control"
                        value="{{ $user->mahasiswa->nim }}" required readonly>
                    <small id="error-mahasiswa_nim" class="error-text form-text text-danger"></small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Daftar</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Inisialisasi validasi
        $("#form-tambah").validate({
            rules: {
                lomba_id: { required: true },
                mahasiswa_nim: { required: true },
            },
            submitHandler: function (form) {
                let formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#myModal').modal('hide');
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '/pendaftaran';
                            });
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
                    error: function (xhr, status, error) {
                        $('#myModal').modal('hide');
                        console.error("AJAX Error:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal mengirim data. Silakan coba lagi.'
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

        // Handler saat memilih lomba
        $('#lomba_id').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.get(`/pendaftaran/${id}/detail`, function (res) {
                    if (res.status) {
                        $('#info-lomba').removeClass('d-none');
                        $('#penyelenggara').val(res.data.penyelenggara || '-');
                        $('#tanggal_pelaksanaan').val(res.data.tanggal_pelaksanaan || '');
                        $('#tingkat').val(res.data.tingkat || '-');
                        $('#kategori').val(res.data.kategori || '-');
                    } else {
                        $('#info-lomba').addClass('d-none');
                    }
                }).fail(function () {
                    $('#info-lomba').addClass('d-none');
                });
            } else {
                $('#info-lomba').addClass('d-none');
            }
        });

        // Auto-load info lomba jika ada nilai lama (dari PHP)
        @if(isset($lombaTerpilih))
            $('#lomba_id').trigger('change');
        @endif
    });
</script>