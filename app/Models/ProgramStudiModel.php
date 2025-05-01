<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudiModel extends Model
{
    protected $table = 'program_studi';
    protected $fillable = ['nama_prodi', 'kode_prodi', 'jenjang'];

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(MahasiswaModel::class);
    }

    public function dosen(): HasMany
    {
        return $this->hasMany(DosenModel::class);
    }
}

?>