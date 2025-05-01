<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiModel extends Model
{
    protected $table = 'prestasi';
    protected $fillable = ['nama_prestasi', 'lomba_id', 'file_bukti', 'status', 'catatan'];

    public function lomba(): BelongsTo
    {
        return $this->belongsTo(LombaModel::class);
    }
}

?>