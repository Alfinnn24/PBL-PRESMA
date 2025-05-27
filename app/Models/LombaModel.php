<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LombaModel extends Model
{
    protected $table = 'lomba';
    protected $fillable = ['nama', 'penyelenggara', 'tingkat', 'bidang_keahlian_id', 'persyaratan', 'jumlah_peserta', 'link_registrasi', 'tanggal_mulai', 'tanggal_selesai', 'periode_id', 'created_by', 'is_verified'];

    protected $dates = ['tanggal_mulai', 'tanggal_selesai'];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeModel::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'created_by');
    }

    public function bidangKeahlian(): BelongsTo
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'bidang_keahlian_id');
    }

    public function rekomendasi(): HasMany
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'lomba_id', 'id');
    }

}

?>