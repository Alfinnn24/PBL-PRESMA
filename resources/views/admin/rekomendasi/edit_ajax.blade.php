@empty($rekomendasi)
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
                    Data rekomendasi tidak ditemukan
                </div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/rekomendasi/' . $rekomendasi->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        <input type="hidden" name="_method" value="PUT">

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Ganti Dosen Pembimbing</label>
                        <select name="dosen_id" id="dosen_id" class="form-control" required>
                            <option value="">- Pilih Dosen -</option>
                            @foreach($daftarDosen as $dosen)
                                <option value="{{ $dosen->id }}" @selected($rekomendasi->dosen_id == $dosen->id)>
                                    {{ $dosen->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="info-dosen" class="{{ $rekomendasi->dosen ? '' : 'd-none' }}">
                        <div class="form-group">
                            <label>NIDN</label>
                            <input type="text" class="form-control" id="nidn" value="{{ $rekomendasi->dosen->nidn ?? '-' }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Program Studi</label>
                            <input type="text" class="form-control" id="prodi"
                                value="{{ $rekomendasi->dosen->programStudi->nama_prodi ?? '-' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp"
                                value="{{ $rekomendasi->dosen->no_telp ?? '-' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Bidang Minat</label>
                            <input type="text" class="form-control" id="bidang_minat"
                                value="{{ $rekomendasi->dosen && $rekomendasi->dosen->bidangMinat ? $rekomendasi->dosen->bidangMinat->map(fn($minat) => $minat->bidangMinat->bidang_minat)->implode(', ') : '-' }}"
                                readonly>
                        </div>
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
            // Validasi form rekomendasi lomba
            $("#form-edit").validate({
                rules: {
                    dosen_id: { required: true },
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
                                tableRekomendasi.ajax.reload(); // Sesuaikan dengan nama variabel DataTable kamu
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

            // Dropdown dosen -> tampilkan detail dosen
            $('#dosen_id').on('change', function () {
                let id = $(this).val();
                if (id) {
                    $.get("/rekomendasi/dosen/" + id + "/detail", function (res) {
                        if (res.status) {
                            $('#info-dosen').removeClass('d-none');
                            $('#nidn').val(res.data.nidn || '-');
                            $('#prodi').val(res.data.program_studi || '-');
                            $('#no_telp').val(res.data.no_telp || '-');
                            $('#bidang_minat').val(res.data.bidang_minat || '-');
                        } else {
                            $('#info-dosen').addClass('d-none');
                        }
                    }).fail(function () {
                        $('#info-dosen').addClass('d-none');
                    });
                } else {
                    $('#info-dosen').addClass('d-none');
                }
            });
        });
    </script>

@endempty