@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card shadow">
            {{-- <div class="card-header">
                <h3>Edit Profil</h3>
            </div> --}}
            <div class="card-body">
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <!-- Upload Foto Profil dengan Preview dan Overlay -->
                    <div class="form-group text-center">
                        {{-- profile pict --}}
                        <div class="d-flex flex-column align-items-center mb-3">
                            <strong class="mb-2">Upload Foto Profil</strong>
                            <label class="position-relative" for="profile_picture">
                                <div style="width: 120px; height: 120px; position: relative;">
                                    <img id="preview-image"
                                        src="{{ $detail->foto_profile ? asset('storage/' . $detail->foto_profile) : asset('images/default-profile2.jpg') }}"
                                        alt="Foto Profil" class="rounded-circle"
                                        style="width: 100%; height: 100%; object-fit: cover; object-position: center;">

                                    <div class="overlay rounded-circle"
                                        style="
                                        opacity: 0;
                                        transition: opacity 0.15s;
                                        cursor: pointer;
                                        position: absolute;
                                        top: 0; left: 0;
                                        width: 100%; height: 100%;
                                        background: rgba(0,0,0,0.4);"
                                        onmouseover="this.style.opacity = 1;" onmouseout="this.style.opacity = 0;">
                                        <i class="fas fa-upload position-absolute text-white"
                                            style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <input type="file" name="foto_profile" id="profile_picture" class="d-none"
                            accept="image/jpeg, image/jpg, image/png">
                        @error('foto_profile')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><strong>Personal Information</strong> <i class="fa-solid fa-pencil fa-sm"></i></h5>
                    </div>
                    {{-- kabeh --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <i class="fa-solid fa-pen-to-square fa-sm"></i> <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $detail->nama_lengkap) }}">
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <i class="fa-solid fa-at fa-sm"></i> <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- mahasiswa --}}
                    @if ($user->role === 'mahasiswa')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <i class="fa-solid fa-hashtag fa-sm"></i> <label>NIM</label>
                                <input type="text" class="form-control" value="{{ $detail->nim }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <i class="fa-solid fa-calendar-days fa-sm"></i> <label>Angkatan</label>
                                <input type="text" name="angkatan"
                                    class="form-control @error('angkatan') is-invalid @enderror"
                                    value="{{ old('angkatan', $detail->angkatan) }}" maxlength="4" pattern="\d{4}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,4)"
                                    title="Harus 4 digit angka">
                                @error('angkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <i class="fa-solid fa-book fa-sm"></i> <label>Program Studi</label>
                                <select name="program_studi_id"
                                    class="form-control @error('program_studi_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('program_studi_id', $detail->program_studi_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_studi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <i class="fa-solid fa-mobile-screen-button fa-sm"></i> <label>No. Telp</label>
                                <input type="text" name="no_telp"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp', $detail->no_telp) }}" maxlength="15"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,15)"
                                    title="Nomor telepon 10-15 digit angka">
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <i class="fa-solid fa-location-dot fa-sm"></i> <label>Alamat</label>
                                <input type="text" name="alamat"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat', $detail->alamat) }}">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- dosen --}}
                    @elseif($user->role === 'dosen')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <i class="fa-solid fa-hashtag fa-sm"></i> <label>NIDN</label>
                                <input type="text" class="form-control" value="{{ $detail->nidn }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <i class="fa-solid fa-book fa-sm"></i> <label>Program Studi</label>
                                <select name="program_studi_id"
                                    class="form-control @error('program_studi_id') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($prodi as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('program_studi_id', $detail->program_studi_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_studi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <i class="fa-solid fa-mobile-screen-button fa-sm"></i> <label>No. Telp</label>
                                <input type="text" name="no_telp"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp', $detail->no_telp) }}" maxlength="15"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,15)"
                                    title="Nomor telepon 10-15 digit angka">
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    {{-- Bidang Minat dosen --}}
                    @if ($user->role === 'dosen')
                        <hr>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><strong>Bidang Minat</strong> <i class="fa-solid fa-flask fa-sm"></i></h5>
                            </div>

                            <select name="bidang_minat_ids[]" class="form-control select2" multiple="multiple">
                                @foreach ($bidangMinat as $bm)
                                    <option value="{{ $bm->id }}"
                                        {{ $detail->bidangMinat->contains('id_minat', $bm->id) ? 'selected' : '' }}>
                                        {{ $bm->bidang_minat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- Bidang Keahlian --}}
                    @if ($user->role === 'mahasiswa')
                        <hr>
                        <div class="form-group">
                            <i class="fa-solid fa-star text-warning fa-sm"></i> <label>Bidang Keahlian</label>
                            <select name="bidang_keahlian_ids[]" class="form-control select2" multiple="multiple">
                                @foreach ($bidangKeahlian as $bk)
                                    <option value="{{ $bk->id }}"
                                        {{ in_array($bk->id, $detail->bidangKeahlian->pluck('id_keahlian')->toArray()) ? 'selected' : '' }}>
                                        {{ $bk->keahlian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- Pengalaman --}}
                    @if ($user->role === 'mahasiswa')
                        <hr>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><strong>Pengalaman (Opsional)</strong></h5>
                                <button type="button" class="btn btn-sm btn-primary" id="tambahPengalaman">
                                    <i class="fas fa-plus"></i> Tambah Pengalaman
                                </button>
                            </div>

                            <div id="pengalamanContainer">
                                @if (count(old('pengalaman', $detail->pengalaman)) > 0)
                                    {{-- Tampilkan existing data --}}
                                    @foreach (old('pengalaman', $detail->pengalaman) as $index => $p)
                                        <div class="pengalaman-item mb-3">
                                            <input type="hidden" name="pengalaman_ids[]"
                                                value="{{ $detail->pengalaman[$index]->id ?? '' }}">
                                            <div class="form-row">
                                                <div class="col-md-8">
                                                    <input type="text" name="pengalaman[]" class="form-control"
                                                        value="{{ $p->pengalaman ?? '' }}"
                                                        placeholder="Contoh: Mengikuti kompetisi robotik nasional">
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="kategori[]" class="form-control">
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach ($bidangKeahlian as $keahlian)
                                                            <option value="{{ $keahlian->keahlian }}"
                                                                {{ ($p->kategori ?? '') == $keahlian->keahlian ? 'selected' : '' }}>
                                                                {{ $keahlian->keahlian }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm hapus-pengalaman">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Template kosong untuk tambah baru --}}
                                    <div class="pengalaman-item mb-3">
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <input type="text" name="pengalaman[]" class="form-control"
                                                    placeholder="Contoh: Mengikuti kompetisi robotik nasional">
                                            </div>
                                            <div class="col-md-3">
                                                <select name="kategori[]" class="form-control">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($bidangKeahlian as $keahlian)
                                                        <option value="{{ $keahlian->keahlian }}">
                                                            {{ $keahlian->keahlian }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm hapus-pengalaman">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    {{-- Sertifikasi --}}
                    @if ($user->role === 'mahasiswa')
                        <hr>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><strong>Sertifikasi (Opsional)</strong></h5>
                                <button type="button" class="btn btn-sm btn-primary" id="tambahSertifikasi">
                                    <i class="fas fa-plus"></i> Tambah Sertifikasi
                                </button>
                            </div>

                            <div id="sertifikasiContainer">
                                @if (count(old('sertifikasi_judul', $detail->sertifikasis)) > 0)
                                    @foreach (old('sertifikasi_judul', $detail->sertifikasis) as $index => $judul)
                                        <div class="sertifikasi-item mb-3">
                                            <input type="hidden" name="sertifikasi_ids[]"
                                                value="{{ $detail->sertifikasis[$index]->id ?? '' }}">
                                            <div class="form-row">
                                                <div class="col-md-4">
                                                    <input type="text" name="sertifikasi_judul[]" class="form-control"
                                                        value="{{ old('sertifikasi_judul')[$index] ?? ($judul->judul ?? '') }}"
                                                        placeholder="Contoh: Sertifikasi Data Science">
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="sertifikasi_kategori[]" class="form-control">
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach ($bidangKeahlian as $keahlian)
                                                            <option value="{{ $keahlian->keahlian }}"
                                                                {{ (old('sertifikasi_kategori')[$index] ?? ($detail->sertifikasis[$index]->kategori ?? '')) == $keahlian->keahlian ? 'selected' : '' }}>
                                                                {{ $keahlian->keahlian }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="custom-file">
                                                        <input type="file" name="sertifikat[]"
                                                            class="custom-file-input"
                                                            id="sertifikatFile{{ $index }}">
                                                        <label class="custom-file-label"
                                                            for="sertifikatFile{{ $index }}">
                                                            {{ $detail->sertifikasis[$index]->path ? basename($detail->sertifikasis[$index]->path) : 'PDF/PNG/JPG/JPEG' }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm hapus-sertifikasi">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Template Kosong --}}
                                    <div class="sertifikasi-item mb-3">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <input type="text" name="sertifikasi_judul[]" class="form-control"
                                                    placeholder="Contoh: Sertifikasi Data Science">
                                            </div>
                                            <div class="col-md-3">
                                                <select name="sertifikasi_kategori[]" class="form-control">
                                                    <option value="">Pilih Kategori</option>
                                                    @foreach ($bidangKeahlian as $keahlian)
                                                        <option value="{{ $keahlian->keahlian }}">
                                                            {{ $keahlian->keahlian }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-file">
                                                    <input type="file" name="sertifikat[]" class="custom-file-input"
                                                        id="sertifikatFile0">
                                                    <label class="custom-file-label"
                                                        for="sertifikatFile0">PDF/PNG/JPG/JPEG</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm hapus-sertifikasi">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <hr>
                    <div class="form-group">
                        <i class="fa-solid fa-key fa-sm"></i> <label>Password <small class="text-muted">(kosongkan jika
                                tidak diubah)</small></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <i class="fa-solid fa-key fa-sm"></i> <label class="mt-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>


                </form>
                <div class="d-flex justify-content-end" style="gap: 10px;">
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="button" id="btnSimpan" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            // Preview image saat upload foto profil
            $('#profile_picture').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
            // Tambah pengalaman
            $('#tambahPengalaman').click(function() {
                const template = $('.pengalaman-item').first().clone();
                template.find('input').val('');
                template.find('select').val('');
                template.find('.invalid-feedback').remove();
                template.find('.is-invalid').removeClass('is-invalid');
                template.show().appendTo('#pengalamanContainer');
            });
            // Hapus pengalaman
            $('#pengalamanContainer').on('click', '.hapus-pengalaman', function() {
                if ($('.pengalaman-item').length) {
                    $(this).closest('.pengalaman-item').remove();
                } else {
                    Swal.fire('Peringatan!', 'Minimal harus ada 1 pengalaman', 'warning');
                }
            });
            // Tambah Sertifikasi
            $('#tambahSertifikasi').click(function() {
                const template = $('.sertifikasi-item').first().clone();
                template.find('input[type="text"]').val('');
                template.find('select').val('');
                template.find('input[type="file"]').val('');
                template.find('label.custom-file-label').text('PDF/PNG/JPG/JPEG');
                template.find('input[name="sertifikasi_ids[]"]').val('');
                template.appendTo('#sertifikasiContainer');
            });
            // Hapus Sertifikasi
            $('#sertifikasiContainer').on('click', '.hapus-sertifikasi', function() {
                if ($('.sertifikasi-item').length) {
                    $(this).closest('.sertifikasi-item').remove();
                } else {
                    Swal.fire('Peringatan!', 'Minimal harus ada 1 sertifikasi (boleh kosong semua field).',
                        'warning');
                }
            });
            // bidang keahlian mahasiswa
            $('.select2').select2({
                placeholder: "Pilih Bidang Keahlian",
                allowClear: true
            });
            // bidang minat dosen
            $('select[name="bidang_minat_ids[]"]').select2({
                placeholder: "Pilih Bidang Minat",
                allowClear: true,
                width: '100%'
            });
            // Update nama file saat memilih file
            $('#sertifikasiContainer').on('change', 'input[type="file"]', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).next('label').text(fileName);
            });
            // Modal konfirmasi saat simpan perubahan
            // AJAX form submission
            $('#btnSimpan').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: "Anda yakin ingin menyimpan perubahan data profil?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#profileForm')[0];
                        const formData = new FormData(form);

                        // Tambahkan method PUT
                        formData.append('_method', 'PUT');

                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Update foto profil jika ada
                                if (response.foto_profile) {
                                    $('#preview-image').attr('src', response
                                        .foto_profile);
                                }

                                Swal.fire({
                                    title: 'Sukses!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = response
                                            .redirect; // Ini yang harus ditambahkan
                                    }
                                });
                            },
                            error: function(xhr) {
                                const errors = xhr.responseJSON.errors;
                                const errorMessages = [];

                                // Reset error state
                                $('.is-invalid').removeClass('is-invalid');
                                $('.invalid-feedback').remove();

                                // Tampilkan error untuk setiap field
                                Object.keys(errors).forEach(function(field) {
                                    const input = $('[name="' + field + '"]');
                                    input.addClass('is-invalid');
                                    input.after(
                                        '<div class="invalid-feedback">' +
                                        errors[field][0] + '</div>');
                                    errorMessages.push(errors[field][0]);
                                });

                                Swal.fire({
                                    title: 'Gagal!',
                                    html: errorMessages.join('<br>'),
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
