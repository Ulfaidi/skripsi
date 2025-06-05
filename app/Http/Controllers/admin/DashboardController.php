<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jembatan;
use App\Models\Komponen;
use App\Models\Penilaian;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Desa;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total data
        $totalJembatan = Jembatan::count();
        $totalKomponen = Komponen::count();
        $totalPenilaian = Penilaian::count();
        $totalUser = User::count();
        $totalKecamatan = Kecamatan::count();
        $totalDesa = Desa::count();

        // Mengambil data untuk grafik
        $jembatanData = Jembatan::with(['penilaian' => function ($query) {
            $query->select('id', 'jembatan_kode', 'komponen_kode', 'tahun', 'nilai');
        }, 'kecamatan_satu', 'kecamatan_dua', 'desa_satu', 'desa_dua'])->get();
        $komponenData = Komponen::with(['penilaian' => function ($query) {
            $query->select('id', 'jembatan_kode', 'komponen_kode', 'tahun', 'nilai');
        }])->get();

        // Mengambil data penilaian terbaru
        $penilaianTerbaru = Penilaian::with(['jembatan', 'komponen'])
            ->orderBy('tahun', 'desc')
            ->take(5)
            ->get();

        // Menghitung statistik
        $statistik = [
            'total_jembatan' => $totalJembatan,
            'total_komponen' => $totalKomponen,
            'total_penilaian' => $totalPenilaian,
            'total_user' => $totalUser,
            'total_kecamatan' => $totalKecamatan,
            'total_desa' => $totalDesa,
            'jembatan_data' => $jembatanData,
            'komponen_data' => $komponenData,
            'penilaian_terbaru' => $penilaianTerbaru,
            'total_alternatif' => $totalJembatan,
            'total_kriteria' => $totalKomponen
        ];

        return view('pageadmin.dashboard.index', compact('statistik'));
    }

    public function alternatif()
    {
        $alternatifData = Jembatan::with('penilaian')->get();
        $kriteriaData = Komponen::with('penilaian')->get();

        return view('pageadmin.dashboard.alternatif', compact('alternatifData', 'kriteriaData'));
    }
}
