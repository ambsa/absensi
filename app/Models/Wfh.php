<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wfh extends Model
{
    use HasFactory;

    protected $casts = [
        'id_pegawai' => 'integer',
    ];
    protected $table = 'wfh';
    protected $primaryKey = 'id_wfh';
    protected $fillable = [
        'id_pegawai',
        'tanggal',
        'status',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function datasen()
    {
        return $this->hasOne(Datasen::class, 'id_pegawai', 'id_pegawai')
            ->whereDate('created_at', now()->toDateString());
    }
}
