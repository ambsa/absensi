<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAbsen extends Model
{
    protected $table = 'data_absen';
    protected $primaryKey = 'id_absen';
    public $timestamps = true;

    protected $fillable = [
        'id_pegawai',
        'jam_masuk',
        'jam_pulang',
        'catatan',
        'file_catatan'
    ];
} 