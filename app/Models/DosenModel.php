<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenModel extends Model
{
    protected $table = 'dosen';
    protected $fillable = ['user_id', 'program_studi_id', 'nidn', 'nama_lengkap', 'no_telp', 'bidang_minat', 'foto_profile'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudiModel::class, 'program_studi_id', 'id');
    }

    public function pembimbingMahasiswa(): HasMany
    {
        return $this->hasMany(DosenPembimbingModel::class, 'dosen_id', 'id');
    }

    public function rekomendasi(): HasMany
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'dosen_pembimbing_id');
    }

    // Relasi ke detail bidang minat
    // public function detailBidangMinat(): HasMany
    // {
    //     return $this->hasMany(DetailBidangMinatModel::class, 'dosen_id');
    // }

    // Relasi langsung ke bidang minat (banyak ke banyak)
    public function bidangMinat()
    {
        return $this->hasMany(DetailBidangMinatModel::class, 'dosen_id');
    }
}

?>