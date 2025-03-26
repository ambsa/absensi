<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidCard extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi
    protected $table = 'rfid_cards';

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'card_number',  // Nomor kartu RFID
        'user_id',      // ID pengguna yang terkait dengan kartu RFID
        'created_at',   // Tanggal pembuatan
    ];

    // Relasi dengan tabel users
    public function user()
    {
        // Menghubungkan kartu RFID dengan pengguna (user) melalui user_id
        return $this->belongsTo(User::class);
    }

        // Relasi ke model Attendance
        public function attendances()
        {
            return $this->hasMany(Attendance::class);
        }
}
