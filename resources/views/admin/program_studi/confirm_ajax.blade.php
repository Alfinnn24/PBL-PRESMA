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
                <h5><i class="fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/program_studi') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/program_studi/' . $programStudi->id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Penghapusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>PERINGATAN!</strong> Menghapus program studi ini akan <u>menghapus seluruh data yang terkait</u>, seperti:
                    <ul class="mb-0">
                        <li>Data Mahasiswa</li>
                        <li>Data Mata Kuliah</li>
                        <li>Data Jadwal</li>
                        <li>Relasi lainnya dalam sistem</li>
                    </ul>
                    <p class="mt-2 mb-0">Lanjutkan hanya jika Anda yakin.</p>
                </div>

                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nama Program Studi:</th>
                        <td class="col-9">{{ $programStudi->nama_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kode Program Studi:</th>
                        <td class="col-9">{{ $programStudi->kode_prodi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenjang:</th>
                        <td class="col-9">{{ $programStudi->jenjang }}</td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus Sekarang</button>
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
                                title: 'Berhasil Dihapus',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            if(typeof dataProgramStudi !== 'undefined'){
                                dataProgramStudi.ajax.reload();
                            }
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menghapus',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Server',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
@endempty
