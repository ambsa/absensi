<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang digunakan oleh model ini
    protected $table = 'attendance'; // Nama tabel yang benar

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',
        'rfid_card_id',
        'date',
        'check_in',
        'check_out',
        'status',
    ];
     // Relasi ke model User
     public function user()
     {
         return $this->belongsTo(User::class);
     }
 
     // Relasi ke model RfidCard
     public function rfidCard()
     {
         return $this->belongsTo(RfidCard::class);
     }
}
