<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenPembimbingModel extends Model
{
    protected $table = 'dosen_pembimbing';
    protected $fillable = ['dosen_id', 'mahasiswa_nim'];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(DosenModel::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }
}

?>