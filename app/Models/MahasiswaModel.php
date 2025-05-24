<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;

    protected $fillable = ['nim', 'user_id', 'nama_lengkap', 'angkatan', 'no_telp', 'alamat', 'program_studi_id', 'foto_profile'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudiModel::class);
    }

    // Relasi ke DetailPrestasiModel
    public function prestasi(): HasMany
    {
        return $this->hasMany(DetailPrestasiModel::class, 'mahasiswa_nim', 'nim');
    }

    // Relasi ke PendaftaranLombaModel
    public function pendaftaranLomba(): HasMany
    {
        return $this->hasMany(PendaftaranLombaModel::class, 'mahasiswa_nim', 'nim');
    }

    // Relasi ke DosenPembimbingModel
    public function dosenPembimbing(): HasMany
    {
        return $this->hasMany(DosenPembimbingModel::class, 'mahasiswa_nim', 'nim');
    }

    // Relasi ke RekomendasiLombaModel
    public function rekomendasi(): HasMany
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'mahasiswa_nim', 'nim');
    }

    // Relasi ke SertifikasiModel
    public function sertifikasis()
    {
        return $this->hasMany(SertifikasiModel::class, 'mahasiswa_nim', 'nim');
    }

    // Relasi ke BidangKeahlian
    public function bidangKeahlian(): HasMany
    {
        return $this->hasMany(DetailBidangKeahlianModel::class, 'mahasiswa_nim', 'nim');
    }

    // Relasi ke PengalamanModel
    public function pengalaman()
    {
        return $this->hasMany(PengalamanModel::class, 'mahasiswa_nim', 'nim');
    }
}


?>