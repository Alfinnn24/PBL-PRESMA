<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBidangKeahlianModel extends Model
{
    protected $table = 'detail_bidang_keahlian';
    protected $fillable = ['id_keahlian', 'mahasiswa_nim'];

    public function bidangKeahlian()
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'id_keahlian');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_nim', 'nim');
    }
}

?>