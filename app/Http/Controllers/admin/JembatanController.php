<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jembatan;
use App\Models\Kecamatan;
use App\Models\Desa;
use RealRashid\SweetAlert\Facades\Alert;

class JembatanController extends Controller
{
    /**
     * Menampilkan semua data jembatan.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            // Memuat data jembatan beserta relasi kecamatan dan desa
            $jembatans = Jembatan::with(['kecamatan_satu', 'kecamatan_dua', 'desa_satu', 'desa_dua'])->get();
            // Memuat semua data kecamatan untuk dropdown di form
            $kecamatans = Kecamatan::all();
            return view('pageadmin.jembatan.show', compact('jembatans', 'kecamatans'));
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kesalahan
            Alert::error('Error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Mengambil data desa berdasarkan kode kecamatan untuk AJAX request.
     *
     * @param string $kecamatanKode Kode kecamatan
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDesasByKecamatan($kecamatanKode)
    {
        try {
            // Mencari desa-desa yang memiliki kode kecamatan yang cocok
            $desas = Desa::where('kecamatan_kode', $kecamatanKode)->get();
            return response()->json($desas);
        } catch (\Exception $e) {
            // Mengembalikan response JSON dengan error jika gagal
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data desa: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan form untuk menambah jembatan baru.
     * (Catatan: Fungsi ini mungkin tidak digunakan jika form tambah ada di modal pada halaman index)
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $kecamatans = Kecamatan::all();
            $desas = Desa::all(); // Ini mungkin tidak perlu jika desa dimuat via AJAX
            return view('pageadmin.jembatan.create', compact('kecamatans', 'desas'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Menyimpan data jembatan baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi data input dari request
            $request->validate([
                'kode_jembatan' => 'required|string|max:255|unique:jembatans,kode_jembatan',
                'nama_jembatan' => 'required|string|max:255',
                'kecamatan_satu_kode' => 'required|string|max:255|exists:kecamatans,kode_kecamatan',
                'kecamatan_dua_kode' => 'required|string|max:255|exists:kecamatans,kode_kecamatan',
                'desa_satu_kode' => 'required|string|max:255|exists:desas,kode_desa',
                'desa_dua_kode' => 'required|string|max:255|exists:desas,kode_desa',
                'tahun_pembangunan' => 'required|integer|min:1900|max:' . date('Y'),
                'panjang' => 'required|numeric|min:0',
                'lebar' => 'required|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Menambah validasi tipe dan ukuran foto
            ]);

            $data = $request->all();

            // Handle upload foto jika ada file yang diunggah
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/jembatan'), $filename);
                $data['foto'] = $filename;
            }

            // Membuat record jembatan baru di database
            Jembatan::create($data);

            Alert::success('Berhasil', 'Data jembatan berhasil ditambahkan');
            return redirect()->route('jembatan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menampilkan error validasi jika ada
            Alert::error('Gagal Validasi', $e->getMessage());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Menampilkan pesan error umum jika terjadi kesalahan lain
            Alert::error('Gagal', 'Terjadi kesalahan saat menambah data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menampilkan form edit untuk jembatan tertentu.
     * (Catatan: Fungsi ini mungkin tidak digunakan jika form edit ada di modal pada halaman index)
     *
     * @param int $id ID jembatan
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            // Mencari jembatan berdasarkan ID
            $jembatan = Jembatan::findOrFail($id);
            $kecamatans = Kecamatan::all();
            // Memuat desa-desa terkait untuk pre-selection di form edit
            $desas_satu = Desa::where('kecamatan_kode', $jembatan->kecamatan_satu_kode)->get();
            $desas_dua = Desa::where('kecamatan_kode', $jembatan->kecamatan_dua_kode)->get();
            return view('pageadmin.jembatan.edit', compact('jembatan', 'kecamatans', 'desas_satu', 'desas_dua'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Data jembatan tidak ditemukan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Menyimpan perubahan data jembatan yang sudah ada.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID jembatan yang akan diperbarui
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi data input, mengecualikan kode_jembatan saat ini dari pengecekan unique
            $request->validate([
                'kode_jembatan' => 'required|string|max:255|unique:jembatans,kode_jembatan,' . $id, // Menggunakan ID untuk pengecualian
                'nama_jembatan' => 'required|string|max:255',
                'kecamatan_satu_kode' => 'required|string|max:255|exists:kecamatans,kode_kecamatan',
                'kecamatan_dua_kode' => 'required|string|max:255|exists:kecamatans,kode_kecamatan',
                'desa_satu_kode' => 'required|string|max:255|exists:desas,kode_desa',
                'desa_dua_kode' => 'required|string|max:255|exists:desas,kode_desa',
                'tahun_pembangunan' => 'required|integer|min:1900|max:' . date('Y'),
                'panjang' => 'required|numeric|min:0',
                'lebar' => 'required|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Menambah validasi tipe dan ukuran foto
            ]);

            // Mencari jembatan berdasarkan ID
            $jembatan = Jembatan::findOrFail($id);
            $data = $request->all();

            // Handle upload foto baru dan hapus foto lama jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada dan file tersebut eksis
                if ($jembatan->foto && file_exists(public_path('uploads/jembatan/' . $jembatan->foto))) {
                    unlink(public_path('uploads/jembatan/' . $jembatan->foto));
                }

                $foto = $request->file('foto');
                $filename = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/jembatan'), $filename);
                $data['foto'] = $filename;
            } else {
                // Jika tidak ada foto baru diunggah, pastikan foto lama tetap ada di $data
                // Ini penting jika Anda hanya ingin memperbarui data lain tanpa mengubah foto
                unset($data['foto']); // Hapus key 'foto' dari $data jika tidak ada file baru, agar tidak menimpa dengan null
            }

            // Memperbarui record jembatan di database
            $jembatan->update($data);

            Alert::success('Berhasil', 'Data jembatan berhasil diperbarui');
            return redirect()->route('jembatan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menampilkan error validasi jika ada
            Alert::error('Gagal Validasi', 'Data yang Anda masukkan tidak valid: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Menampilkan pesan error umum jika terjadi kesalahan lain
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Menghapus data jembatan dari database.
     *
     * @param int $id ID jembatan yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Mencari jembatan berdasarkan ID
            $jembatan = Jembatan::findOrFail($id);

            // Menghapus relasi penilaian terlebih dahulu (jika ada)
            // Pastikan model Penilaian memiliki 'jembatan_kode' sebagai foreign key yang mengacu ke 'kode_jembatan' di tabel jembatans
            // Jika relasi ini menggunakan ID jembatan, Anda mungkin perlu menyesuaikan 'jembatan_id'
            // Contoh di sini mengasumsikan 'jembatan_kode' di tabel penilaian merujuk ke 'kode_jembatan' jembatan.
            // Jika itu 'id', maka ubah: $jembatan->penilaian()->delete();
            // Atau jika tidak ada relasi 'penilaian', baris ini bisa dihapus.
            if ($jembatan->penilaian) { // Cek apakah ada relasi penilaian
                $jembatan->penilaian()->delete();
            }


            // Hapus foto jika ada dan file tersebut eksis
            if ($jembatan->foto && file_exists(public_path('uploads/jembatan/' . $jembatan->foto))) {
                unlink(public_path('uploads/jembatan/' . $jembatan->foto));
            }

            // Menghapus record jembatan
            $jembatan->delete();

            Alert::success('Berhasil', 'Data jembatan berhasil dihapus');
            return redirect()->route('jembatan');
        } catch (\Exception $e) {
            // Menampilkan pesan error jika terjadi kesalahan
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
