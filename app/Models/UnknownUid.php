<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnknownUid extends Model
{
    use HasFactory;

    protected $table = 'unknown_uids';
    protected $primaryKey = 'id_unknown';
    public $timestamps = false; // Set to false if your table does not have timestamps
    protected $fillable = ['uuid'];
} 