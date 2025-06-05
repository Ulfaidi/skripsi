<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Jembatan;
use App\Models\Komponen;
use RealRashid\SweetAlert\Facades\Alert;

class PenilaianController extends Controller
{
    public function index()
    {
        $penilaian = Penilaian::all();
        $jembatan = Jembatan::all();
        $komponen = Komponen::all();
        return view('pageadmin.penilaian.show', compact('penilaian', 'jembatan', 'komponen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jembatan_id' => 'required',
            'tahun' => 'required|numeric',
            'komponen' => 'required|array',
        ]);

        // Ambil kode_jembatan dari id
        $jembatan = \App\Models\Jembatan::find($request->jembatan_id);
        if (!$jembatan) {
            Alert::error('Error', 'Jembatan tidak ditemukan');
            return back();
        }

        foreach ($request->komponen as $komponen_id => $nilai) {
            // Ambil kode_komponen dari id
            $komponen = \App\Models\Komponen::find($komponen_id);
            if (!$komponen) continue; // skip jika tidak ditemukan

            Penilaian::create([
                'jembatan_kode' => $jembatan->kode_jembatan,
                'komponen_kode' => $komponen->kode_komponen,
                'tahun' => $request->tahun,
                'nilai' => $nilai,
            ]);
        }

        Alert::success('Berhasil', 'Data penilaian berhasil ditambahkan');
        return redirect()->route('penilaian');
    }


    public function update(Request $request, $jembatan_kode, $tahun)
    {
        $request->validate([
            'komponen' => 'required|array',
            'komponen.*' => 'required|numeric|min:1|max:7',
        ]);

        foreach ($request->komponen as $komponen_kode => $nilai) {
            Penilaian::updateOrCreate(
                [
                    'jembatan_kode' => $jembatan_kode,
                    'komponen_kode' => $komponen_kode,
                    'tahun' => $tahun
                ],
                ['nilai' => $nilai]
            );
        }

        Alert::success('Berhasil', 'Data penilaian berhasil diperbarui');
        return redirect()->route('penilaian');
    }

    public function destroy($jembatan_kode, $tahun)
    {
        try {
            Penilaian::where('jembatan_kode', $jembatan_kode)
                ->where('tahun', $tahun)
                ->delete();
            Alert::success('Berhasil', 'Data penilaian berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data penilaian');
        }
        return redirect()->route('penilaian');
    }
}
