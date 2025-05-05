<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidangMinatModel extends Model
{
    protected $table = 'bidang_minat';
    protected $fillable = ['bidang_minat'];

    public function details()
    {
        return $this->hasMany(DetailBidangMinatModel::class, 'id_minat');
    }
}

?>