<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;
    protected $table = 'departemen';

    // Relasi ke model User
    
    protected $fillable = ['name', 'description'];

    // Relasi ke model User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
