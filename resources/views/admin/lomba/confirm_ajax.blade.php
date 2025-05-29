@empty($lomba)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang anda cari tidak ditemukan</div>
            <a href="{{ url('/lomba') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/lomba/' . $lomba->id.'/delete_ajax') }}" method="POST" id="form-delete">
@csrf
@method('DELETE')
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Lomba</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <h5><i class="fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda ingin menghapus data seperti di bawah ini?
            </div>
            <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Nama Lomba:</th>
                            <td class="col-9">{{ $lomba->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penyelenggara Lomba:</th>
                            <td class="col-9">{{ $lomba->penyelenggara}}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tingkat Lomba:</th>
                            <td class="col-9">{{ $lomba->tingkat }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Bidang Keahlian Lomba:</th>
                            <td class="col-9">{{ $lomba->bidangKeahlian->keahlian }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Persyaratan Lomba:</th>
                            <td class="col-9">{{ $lomba->persyaratan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jumlah Peserta Lomba:</th>
                            <td class="col-9">{{ $lomba->jumlah_peserta }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Link Registrasi Lomba:</th>
                            <td class="col-9">{{ $lomba->link_registrasi }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Mulai Lomba:</th>
                            <td class="col-9">{{ \Carbon\Carbon::parse($lomba->tanggal_mulai)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Selesai Lomba:</th>
                            <td class="col-9">{{ \Carbon\Carbon::parse($lomba->tanggal_selesai)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Periode Lomba:</th>
                            <td class="col-9">{{ $lomba->periode->display_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Pembuat Lomba:</th>
                            <td class="col-9">{{ $lomba->creator->full_name ?? 'Tidak Diketahui' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Status Lomba:</th>
                            <td class="col-9">{{ $lomba->is_verified }}</td>
                        </tr>
                    </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            rules: {},
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if(response.status){
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataLomba.ajax.reload();
                        }else{
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-'+prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
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
@endempty