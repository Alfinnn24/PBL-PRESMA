<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPrestasiModel extends Model
{
    protected $table = 'detail_prestasi';
    protected $fillable = ['mahasiswa_nim', 'prestasi_id'];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(PrestasiModel::class);
    }
}

?>