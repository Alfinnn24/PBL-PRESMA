<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SertifikasiModel extends Model
{
    protected $table = 'sertifikasi';

    protected $fillable = [
        'judul',
        'path',
        'mahasiswa_nim',
    ];

    /**
     * Relasi ke model Mahasiswa
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }
}
