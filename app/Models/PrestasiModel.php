<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrestasiModel extends Model
{
    protected $table = 'prestasi';
    protected $fillable = ['nama_prestasi', 'lomba_id', 'file_bukti', 'status', 'catatan'];

    public function lomba(): BelongsTo
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id', 'id');
    }

    public function detailPrestasi(): HasMany
    {
        return $this->hasMany(DetailPrestasiModel::class, 'prestasi_id', 'id'); // pastikan 'prestasi_id' dan 'id' sesuai dengan kolom di tabel
    }
}

?>