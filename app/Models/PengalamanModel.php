<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengalamanModel extends Model
{
    protected $table = 'pengalaman';
    protected $fillable = ['pengalaman'];

    public function details()
    {
        return $this->hasMany(DetailPengalamanModel::class, 'id_pengalaman');
    }
}

?>