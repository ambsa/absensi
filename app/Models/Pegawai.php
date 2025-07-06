<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pegawai extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $primaryKey = 'id_pegawai'; // Mengatur primary key menjadi id_pegawai
    protected $table = 'pegawai';        // Nama tabel

    // Kolom yang dapat diisi secara mass assignment
    protected $fillable = [
        'nama_pegawai',
        'email',
        'password',
        'id_role',         // Kolom role_id di tabel pegawai
        'id_departemen',   // Kolom departemen_id di tabel pegawai
        'uuid',
        'device_token',
    ];

    // Kolom yang disembunyikan dari array/json
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Konversi tipe data kolom tertentu
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Menggunakan timestamps
    public $timestamps = true;

    // Relasi ke model Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    // Relasi ke Departemen
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen', 'id_departemen');
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class, 'id_pegawai');
    }
    public function wfh()
    {
        return $this->hasMany(Wfh::class, 'id_pegawai', 'id_pegawai');
    }
}
