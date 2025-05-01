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

    protected $fillable = ['user_id', 'nama_lengkap', 'angkatan', 'no_telp', 'alamat', 'program_studi_id', 'bidang_keahlian', 'sertifikasi', 'pengalaman', 'foto_profile'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudiModel::class);
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(DetailPrestasiModel::class, 'mahasiswa_nim', 'nim');
    }

    public function pendaftaranLomba(): HasMany
    {
        return $this->hasMany(PendaftaranLombaModel::class, 'mahasiswa_nim', 'nim');
    }

    public function dosenPembimbing(): HasMany
    {
        return $this->hasMany(DosenPembimbingModel::class, 'mahasiswa_nim', 'nim');
    }

    public function rekomendasi(): HasMany
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'mahasiswa_nim', 'nim');
    }
}

?>