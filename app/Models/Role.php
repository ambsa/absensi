<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_role';
    
    protected $table = 'role';

    protected $fillable = [
        'name',
    ];


    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_role', 'id_role');
    }
    
}
