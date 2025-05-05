<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBidangMinatModel extends Model
{
    protected $table = 'detail_bidang_minat';
    protected $fillable = ['id_minat', 'dosen_id'];

    public function bidangMinat()
    {
        return $this->belongsTo(BidangMinatModel::class, 'id_minat');
    }
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }
}

?>