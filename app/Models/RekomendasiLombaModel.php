<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekomendasiLombaModel extends Model
{
    protected $table = 'rekomendasi_lomba';
    protected $fillable = ['mahasiswa_nim', 'lomba_id', 'dosen_pembimbing_id', 'status', 'status_dosen', 'skor'];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }

    public function lomba(): BelongsTo
    {
        return $this->belongsTo(LombaModel::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenModel::class, 'dosen_pembimbing_id');
    }
}

?>