<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',  // Tambahkan role_id
        'departemen_id',  // Tambahkan departemen_id
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     // Relasi ke model Role
     public function role(): BelongsTo
     {
         return $this->belongsTo(Role::class);
     }
 
     // Relasi ke model Departemen
     public function departemen(): BelongsTo
     {
         return $this->belongsTo(Departemen::class);
     }

       // Relasi dengan tabel rfid_cards
    public function rfidCard()
    {
        return $this->hasMany(RfidCard::class);
    }
 
     // Relasi ke model Attendance
     public function attendances()
     {
         return $this->hasMany(Attendance::class);
     }
    
     public function workSchedule()
     {
         return $this->hasMany(WorkSchedule::class);
     }
}
