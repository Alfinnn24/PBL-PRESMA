<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    protected $table = 'periode';
    protected $fillable = ['nama', 'tahun', 'semester'];
}

?>