<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'nama_jembatan',
        'desa_satu',
        'desa_dua',
        'preferensi',
        'tahun'
    ];
}
