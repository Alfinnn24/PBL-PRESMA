<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidangKeahlianModel extends Model
{
    protected $table = 'bidang_keahlian';
    protected $fillable = ['keahlian'];

    public function details()
    {
        return $this->hasMany(DetailBidangKeahlianModel::class, 'id_keahlian');
    }
}

?>