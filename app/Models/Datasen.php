<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datasen extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'data_absen';

    // Kolom utama (primary key)
    protected $primaryKey = 'id_absen'; // Sesuaikan dengan nama kolom primary key

    // Tidak perlu menonaktifkan incrementing karena id_absen adalah auto-increment
    public $incrementing = true; // Default: true

    // Tipe data primary key
    protected $keyType = 'int'; // Default: int

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'id_pegawai',
        'jam_masuk',
        'jam_pulang',
        'catatan',
        'file_catatan',
    ];

    // Timestamps (jika kolom created_at dan updated_at ada di tabel)
    public $timestamps = true; // Default: true

    public function pegawai()
{
    return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
}
}
