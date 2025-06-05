<?php

namespace App\Http\Controllers\admin;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class DesaController extends Controller
{
    public function fetchAndStoreDesa(Request $request)
    {
        // Ambil semua kecamatan
        $kecamatans = Kecamatan::all();

        foreach ($kecamatans as $kecamatan) {
            $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/villages/' . $kecamatan->kode_kecamatan . '.json');
            $data = $response->json();

            foreach ($data as $item) {
                Desa::create([
                    'kode_desa' => $item['id'],
                    'nama_desa' => $item['name'],
                    'kecamatan_kode' => $item['district_id']
                ]);
            }
        }

        return response()->json(['message' => 'Data desa berhasil disimpan']);
    }
    

    public function index()
    {
        $desas = Desa::with('kecamatan')->get();
        $kecamatans = Kecamatan::all();
        return view('pageadmin.wilayah.desa.index', compact('desas', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_desa' => 'required|string|max:255|unique:desas,kode_desa',
            'nama_desa' => 'required|string|max:255',
            'kecamatan_kode' => 'required|exists:kecamatans,kode_kecamatan'
        ]);

        try {
            Desa::create($request->all());
            Alert::success('Berhasil', 'Data desa berhasil ditambahkan');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data desa');
        }

        return redirect()->route('desa');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_desa' => 'required|string|max:255|unique:desas,kode_desa,' . $id,
            'nama_desa' => 'required|string|max:255',
            'kecamatan_kode' => 'required|exists:kecamatans,kode_kecamatan'
        ]);

        try {
            $desa = Desa::findOrFail($id);
            $desa->update($request->all());
            Alert::success('Berhasil', 'Data desa berhasil diperbarui');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal memperbarui data desa');
        }

        return redirect()->route('desa');
    }

    public function destroy($id)
    {
        try {
            $desa = Desa::findOrFail($id);
            $desa->delete();
            Alert::success('Berhasil', 'Data desa berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data desa');
        }

        return redirect()->route('desa');
    }
}
