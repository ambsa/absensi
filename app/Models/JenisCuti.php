<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    protected $table = 'jenis_cuti';

       // Override nama primary key
       protected $primaryKey = 'id_jenis_cuti';

       // Jika primary key bukan auto-increment, tambahkan ini:
       public $incrementing = false;
}
