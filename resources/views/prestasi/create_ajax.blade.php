<form action="{{ url('/prestasi/ajax') }}" method="POST" id="form-tambah" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Prestasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Nama Prestasi</label>
                    <input type="text" name="nama_prestasi" id="nama_prestasi" class="form-control" required>
                    <small id="error-nama_prestasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Lomba</label>
                    <select name="lomba_id" id="lomba_id" class="form-control" required>
                        <option value="">- Pilih Lomba -</option>
                        @foreach($lomba as $l)
                            <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span>Tidak ada lomba yang tersedia?</span>
                        <a href="{{ url('/rekomendasi') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            + Tambah Lomba
                        </a>
                    </div>
                    <small id="error-lomba_id" class="error-text form-text text-danger"></small>
                </div>

                <div id="info-lomba" class="mt-3 d-none">
                    <div class="form-group">
                        <label>Penyelenggara</label>
                        <input type="text" class="form-control" id="penyelenggara" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Perolehan</label>
                        <input type="date" class="form-control" id="tanggal_perolehan" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tingkat Lomba</label>
                        <input type="text" class="form-control" id="tingkat" readonly>
                    </div>
                    <div class="form-group">
                        <label>Bidang Keahlian / Kategori</label>
                        <input type="text" class="form-control" id="kategori" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mahasiswa</label>
                    <input type="text" name="mahasiswa_nim[]" id="mahasiswa_nim[]" class="form-control"
                        value="{{ $user->mahasiswa->nim }}" required readonly>
                    <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
                </div>


                <div class="form-group">
                    <label>File Bukti (PDF/Gambar)</label>
                    <input type="file" name="file_bukti" id="file_bukti" class="form-control"
                        accept="application/pdf,image/png,image/jpeg,image/jpg,image/webp" required>
                    <small id="error-file_bukti" class="error-text form-text text-danger"></small>
                </div>

                <!-- <div class="form-group d-none">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">- Pilih Status -</option>
                        <option value="Pending">Pending</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div> -->

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                    <small id="error-catatan" class="error-text form-text text-danger"></small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

{{-- Script --}}
<script>
    $(document).ready(function () {

        // Validasi form dan pengiriman via AJAX
        $("#form-tambah").validate({
            rules: {
                nama_prestasi: { required: true, minlength: 3 },
                lomba_id: { required: true },
                'mahasiswa_nim[]': { required: true },
                file_bukti: { required: true },
                catatan: { required: false },
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
                        $('#myModal').modal('hide');  // Tutup modal setelah berhasil
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPrestasi.ajax.reload();
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
                        $('#myModal').modal('hide');  // Tutup modal meskipun terjadi error
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
    });

    $('#lomba_id').on('change', function () {
        let id = $(this).val();
        if (id) {
            $.get("/prestasi/lomba/" + id + "/detail", function (res) {
                if (res.status) {
                    $('#info-lomba').removeClass('d-none');
                    $('#penyelenggara').val(res.data.penyelenggara || '-');
                    $('#tanggal_perolehan').val(res.data.tanggal_perolehan || '');
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

</script>