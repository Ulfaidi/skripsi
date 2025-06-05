<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;
    protected $fillable = ['kode_desa', 'nama_desa', 'kecamatan_kode'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_kode', 'kode_kecamatan');
    }

    public function getKecamatanNameAttribute()
    {
        return $this->kecamatan->nama_kecamatan;
    }

    public function jembatans()
    {
        return $this->hasMany(Jembatan::class, 'desa_kode', 'kode_desa');
    }
}
