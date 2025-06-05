<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;
    protected $table = 'penilaians';
    protected $fillable = ['jembatan_kode', 'komponen_kode', 'tahun', 'nilai'];

    public function jembatan()
    {
        return $this->belongsTo(Jembatan::class, 'jembatan_kode', 'kode_jembatan');
    }
    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'komponen_kode', 'kode_komponen');
    }

    public function scopeFilterTahun($query, $tahun)
    {
        return $query->when($tahun, function ($q) use ($tahun) {
            return $q->where('tahun', $tahun);
        });
    }

    public static function getNilaiByJembatanAndTahun($jembatan_kode, $tahun)
    {
        return self::where('jembatan_kode', $jembatan_kode)
            ->where('tahun', $tahun)
            ->pluck('nilai', 'komponen_kode')
            ->toArray();
    }
}
