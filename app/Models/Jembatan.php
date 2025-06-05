<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jembatan extends Model
{
    use HasFactory;
    protected $table = 'jembatans';
    protected $fillable = ['kode_jembatan', 'nama_jembatan', 'kecamatan_satu_kode', 'kecamatan_dua_kode', 'desa_satu_kode', 'desa_dua_kode', 'tahun_pembangunan', 'panjang', 'lebar', 'foto'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'jembatan_kode', 'kode_jembatan');
    }

    public function getNamaAttribute()
    {
        return $this->nama_jembatan;
    }
    public function kecamatan_satu()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_satu_kode', 'kode_kecamatan');
    }
    public function kecamatan_dua()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_dua_kode', 'kode_kecamatan');
    }
    public function desa_satu()
    {
        return $this->belongsTo(Desa::class, 'desa_satu_kode', 'kode_desa');
    }
    public function desa_dua()
    {
        return $this->belongsTo(Desa::class, 'desa_dua_kode', 'kode_desa');
    }
}
