<?php

namespace App\Http\Controllers\admin;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class KecamatanController extends Controller
{
    public function fetchAndStoreKecamatan(Request $request)
    {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/1401.json');
        $data = $response->json();

        foreach ($data as $item) {
            Kecamatan::create([
                'kode_kecamatan' => $item['id'],
                'nama_kecamatan' => $item['name']
            ]);
        }

        return response()->json(['message' => 'Data kecamatan berhasil disimpan']);
    }

    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('pageadmin.wilayah.kecamatan.index', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kecamatan' => 'required|string|max:255|unique:kecamatans,kode_kecamatan',
            'nama_kecamatan' => 'required|string|max:255',
        ]);

        try {
            Kecamatan::create($request->all());
            Alert::success('Berhasil', 'Data kecamatan berhasil ditambahkan');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data kecamatan');
        }

        return redirect()->route('kecamatan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kecamatan' => 'required|string|max:255|unique:kecamatans,kode_kecamatan,' . $id,
            'nama_kecamatan' => 'required|string|max:255',
        ]);

        try {
            $kecamatan = Kecamatan::findOrFail($id);
            $kecamatan->update($request->all());
            Alert::success('Berhasil', 'Data kecamatan berhasil diperbarui');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal memperbarui data kecamatan');
        }

        return redirect()->route('kecamatan');
    }

    public function destroy($id)
    {
        try {
            $kecamatan = Kecamatan::findOrFail($id);
            $kecamatan->delete();
            Alert::success('Berhasil', 'Data kecamatan berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data kecamatan');
        }

        return redirect()->route('kecamatan');
    }
}
