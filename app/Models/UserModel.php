<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';

    protected $primaryKey = 'id';

    protected $fillable = ['username', 'email', 'password', 'role', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function getRole()
    {
        return $this->role;
    }

    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id', 'id');
    }

    public function dosen()
    {
        return $this->hasOne(DosenModel::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'user_id', 'id');
    }

    public function lombas()
    {
        return $this->hasMany(LombaModel::class, 'created_by');
    }
}
