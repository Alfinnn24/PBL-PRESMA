<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengalamanModel extends Model
{
    protected $table = 'detail_pengalaman';
    protected $fillable = ['id_pengalaman', 'mahasiswa_nim'];

    public function pengalaman()
    {
        return $this->belongsTo(PengalamanModel::class, 'id_pengalaman');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }
}

?>