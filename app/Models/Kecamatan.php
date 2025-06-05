<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = 'kecamatans';
    protected $fillable = ['kode_kecamatan', 'nama_kecamatan'];

    public function desas()
    {
        return $this->hasMany(Desa::class, 'kecamatan_kode', 'kode_kecamatan');
    }
    public function jembatans()
    {
        return $this->hasMany(Jembatan::class, 'kecamatan_kode', 'kode_kecamatan');
    }
}
