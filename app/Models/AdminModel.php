<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $fillable = ['nama_lengkap', 'user_id', 'foto_profile'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}

?>