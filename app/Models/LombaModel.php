<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LombaModel extends Model
{
    protected $table = 'lomba';
    protected $fillable = ['nama', 'kategori', 'penyelenggara', 'tingkat', 'bidang_keahlian', 'persyaratan', 'link_registrasi', 'tanggal_mulai', 'tanggal_selesai', 'periode_id', 'created_by', 'is_verified'];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeModel::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'created_by');
    }
}

?>