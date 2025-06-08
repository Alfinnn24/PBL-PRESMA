@empty($prestasi)
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
                    Data prestasi tidak ditemukan
                </div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/prestasi/' . $prestasi->id . '/update_ajax') }}" method="POST" id="form-edit"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PUT">

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Prestasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Prestasi</label>
                        <input value="{{ $prestasi->nama_prestasi }}" type="text" name="nama_prestasi" id="nama_prestasi"
                            class="form-control" required>
                        <small id="error-nama_prestasi" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Lomba</label>
                        <select name="lomba_id" id="lomba_id" class="form-control" required>
                            <option value="">- Pilih Lomba -</option>
                            @foreach($lomba as $l)
                                <option value="{{ $l->id }}" @selected($prestasi->lomba_id == $l->id)>{{ $l->nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-lomba_id" class="error-text text-danger"></small>
                    </div>

                    <div id="info-lomba" class="{{ $prestasi->lomba ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>Penyelenggara</label>
                            <input type="text" class="form-control" id="penyelenggara"
                                value="{{ $prestasi->lomba->penyelenggara ?? '-' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Perolehan</label>
                            <input type="text" class="form-control" id="tanggal_perolehan"
                                value="{{ $prestasi->lomba && $prestasi->lomba->tanggal_selesai ? \Carbon\Carbon::parse($prestasi->lomba->tanggal_selesai)->format('d/m/Y') : '-' }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Tingkat Lomba</label>
                            <input type="text" class="form-control" id="tingkat"
                                value="{{ $prestasi->lomba->tingkat ?? '-' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Bidang Keahlian / Kategori</label>
                            <input type="text" class="form-control" id="kategori"
                                value="{{ $prestasi->lomba->bidangKeahlian->keahlian ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>File Bukti (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="file" name="file_bukti" id="file_bukti" class="form-control"
                            accept="application/pdf,image/png,image/jpeg,image/jpg,image/webp">
                        <small id="error-file_bukti" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control"
                            rows="3">{{ $prestasi->catatan }}</textarea>
                        <small id="error-catatan" class="error-text text-danger"></small>
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
            $("#form-edit").validate({
                rules: {
                    nama_prestasi: { required: true, minlength: 3 },
                    lomba_id: { required: true },
                    file_bukti: { required: false },
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#myModal').modal('hide');
                            if (response.status) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                                dataPrestasi.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                if (response.msgField) {
                                    $.each(response.msgField, function (prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
                                }
                                Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                            }
                        },
                        error: function () {
                            $('#myModal').modal('hide');
                            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Gagal mengirim data.' });
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
@endempty