<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    use HasFactory;
    protected $table = 'komponens';
    protected $fillable = ['kode_komponen', 'nama_komponen', 'bobot', 'tipe'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'komponen_kode', 'kode_komponen');
    }
}
