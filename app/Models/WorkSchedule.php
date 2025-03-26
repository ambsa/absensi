<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $table = 'work_schedule';
    protected $fillable = ['user_id', 'day', 'start', 'end'];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

