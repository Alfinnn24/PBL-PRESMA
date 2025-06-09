<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotKriteriaModel extends Model
{
    use HasFactory;
    protected $table = 'bobot_kriteria';

    protected $fillable = ['kriteria', 'bobot'];

    public $timestamps = true;
}
