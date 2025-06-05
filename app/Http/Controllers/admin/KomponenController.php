<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen;
use RealRashid\SweetAlert\Facades\Alert;

class KomponenController extends Controller
{
    public function index()
    {
        try {
            $komponens = Komponen::all();
            $totalBobot = Komponen::sum('bobot');
            return view('pageadmin.komponen.show', compact('komponens', 'totalBobot'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function create()
    {
        return view('pageadmin.komponen.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode_komponen' => 'required|string|max:255|unique:komponens,kode_komponen',
                'nama_komponen' => 'required|string|max:255',
                'bobot' => 'required|numeric|min:0|max:100',
                'tipe' => 'required|string|in:benefit,cost'
            ]);

            Komponen::create($request->all());

            Alert::success('Berhasil', 'Data komponen berhasil ditambahkan');
            return redirect()->route('komponen');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menambahkan data');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $komponen = Komponen::findOrFail($id);
        return view('pageadmin.komponen.edit', compact('komponen'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kode_komponen' => 'required|string|max:255|unique:komponens,kode_komponen,' . $id,
                'nama_komponen' => 'required|string|max:255',
                'bobot' => 'required|numeric|min:0|max:100',
                'tipe' => 'required|string|in:benefit,cost'
            ]);

            $komponen = Komponen::findOrFail($id);
            $komponen->update($request->all());

            Alert::success('Berhasil', 'Data komponen berhasil diperbarui');
            return redirect()->route('komponen');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data');
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $komponen = Komponen::findOrFail($id);
            $komponen->delete();

            Alert::success('Berhasil', 'Data komponen berhasil dihapus');
            return redirect()->route('komponen');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus data');
            return redirect()->back();
        }
    }
}
