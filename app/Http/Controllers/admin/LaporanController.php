<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jembatan;
use App\Models\Penilaian;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun_list = Laporan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $data = [];
        foreach ($tahun_list as $tahun) {
            $data[$tahun] = Laporan::where('tahun', $tahun)
                ->orderBy('preferensi', 'desc')
                ->get();
        }

        return view('pageadmin.laporan.show', compact('data', 'tahun_list'));
    }

    // Untuk cetak laporan per tahun
    public function cetak($tahun)
    {
        $laporan = Laporan::where('tahun', $tahun)
            ->orderBy('preferensi', 'desc')
            ->get();

        return view('pageadmin.laporan.cetak', compact('laporan', 'tahun'));
    }
}
