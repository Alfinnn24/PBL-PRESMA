<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function prestasi()
    {
        return $this->hasMany(PrestasiModel::class, 'created_by');
    }

    public function getFullNameAttribute()
    {
        switch ($this->role) {
            case 'mahasiswa':
                return $this->mahasiswa ? $this->mahasiswa->nama_lengkap : $this->username;
            case 'dosen':
                return $this->dosen ? $this->dosen->nama_lengkap : $this->username;
            case 'admin':
                return $this->admin ? $this->admin->nama_lengkap : $this->username;
            default:
                return $this->username;
        }
    }

}
