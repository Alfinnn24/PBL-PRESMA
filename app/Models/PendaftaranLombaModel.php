<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranLombaModel extends Model
{
    protected $table = 'pendaftaran_lomba';
    protected $fillable = ['mahasiswa_nim', 'lomba_id', 'status', 'hasil'];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }

    public function lomba(): BelongsTo
    {
        return $this->belongsTo(LombaModel::class, 'lomba_id', 'id');
    }
}

?>