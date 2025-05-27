<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;
    protected $table = 'departemen';

    protected $primaryKey = 'id_departemen';

    // Relasi ke model User
    
    protected $fillable = ['nama_departemen'];

    // Relasi ke model User
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_departemen', 'id_departemen');
    }
}
