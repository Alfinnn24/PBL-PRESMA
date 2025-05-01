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
 

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function lombas()
    {
        return $this->hasMany(Lomba::class, 'created_by');
    }
}
