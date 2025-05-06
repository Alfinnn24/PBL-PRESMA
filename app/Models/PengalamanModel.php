<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengalamanModel extends Model
{
    protected $table = 'pengalaman';

    protected $fillable = [
        'pengalaman',
        'kategori',
        'mahasiswa_nim',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }
}

?>