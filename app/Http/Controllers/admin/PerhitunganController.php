<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jembatan;
use App\Models\Komponen;
use App\Models\Penilaian;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PerhitunganController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');

        // Ambil data jembatan yang memiliki penilaian
        $jembatan_kodes = Penilaian::filterTahun($tahun)
            ->select('jembatan_kode')
            ->distinct()
            ->pluck('jembatan_kode');

        $jembatan = Jembatan::whereIn('kode_jembatan', $jembatan_kodes)->get();
        $komponen = Komponen::all();

        // Ambil nilai dalam bentuk matriks
        $nilai = [];
        foreach ($jembatan as $j) {
            $nilai[$j->id] = [];
            foreach ($komponen as $k) {
                $nilai[$j->id][$k->id] = (int)Penilaian::where('jembatan_kode', $j->kode_jembatan)
                    ->where('komponen_kode', $k->kode_komponen)
                    ->filterTahun($tahun)
                    ->value('nilai') ?? 0;
            }
        }

        // Perhitungan TOPSIS
        $jumlah_komponen = $komponen->count();
        $jumlah_jembatan = $jembatan->count();

        // Normalisasi Matriks
        $norm_matrix = [];
        foreach ($komponen as $k) {
            $sum_square = array_sum(array_map(fn($v) => pow($v[$k->id], 2), $nilai));
            $sqrt_sum = sqrt($sum_square);

            foreach ($jembatan as $j) {
                $norm_matrix[$j->id][$k->id] = ($sqrt_sum == 0) ? 0 : $nilai[$j->id][$k->id] / $sqrt_sum;
            }
        }

        // Matriks Terbobot
        $weighted_matrix = [];
        $total_bobot = $komponen->sum('bobot');

        foreach ($jembatan as $j) {
            foreach ($komponen as $k) {
                $bobot_normal = $k->bobot / $total_bobot;
                $weighted_matrix[$j->id][$k->id] = $norm_matrix[$j->id][$k->id] * $bobot_normal;
            }
        }

        // Solusi Ideal Positif dan Negatif
        $A_pos = [];
        $A_neg = [];
        foreach ($komponen as $k) {
            $column_values = array_column($weighted_matrix, $k->id);
            $A_pos[$k->id] = !empty($column_values) ? ($k->tipe == 'benefit' ? max($column_values) : min($column_values)) : 0;
            $A_neg[$k->id] = !empty($column_values) ? ($k->tipe == 'benefit' ? min($column_values) : max($column_values)) : 0;
        }

        // Menghitung Jarak ke Solusi Ideal
        $D_pos = [];
        $D_neg = [];
        foreach ($jembatan as $j) {
            $D_pos[$j->id] = sqrt(array_sum(array_map(fn($x, $a) => pow($x - $a, 2), $weighted_matrix[$j->id], $A_pos)));
            $D_neg[$j->id] = sqrt(array_sum(array_map(fn($x, $a) => pow($x - $a, 2), $weighted_matrix[$j->id], $A_neg)));
        }

        // Menghitung Skor Preferensi
        $P = [];
        foreach ($jembatan as $j) {
            $P[$j->id] = ($D_pos[$j->id] + $D_neg[$j->id]) == 0 ? 0 : $D_neg[$j->id] / ($D_pos[$j->id] + $D_neg[$j->id]);
        }

        // Menyusun Peringkat Jembatan
        arsort($P);
        $ranking = [];
        foreach ($P as $id => $score) {
            $ranking[Jembatan::find($id)->nama_jembatan] = $score;
        }

        // Ambil daftar tahun untuk filter
        $tahun_list = Penilaian::select('tahun')
            ->selectRaw('COUNT(DISTINCT jembatan_kode) as jumlah_jembatan')
            ->groupBy('tahun')
            ->having('jumlah_jembatan', '>=', 2)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('pageadmin.perhitungan.show', compact(
            'jembatan',
            'komponen',
            'nilai',
            'norm_matrix',
            'weighted_matrix',
            'A_pos',
            'A_neg',
            'D_pos',
            'D_neg',
            'ranking',
            'tahun_list',
            'tahun'
        ));
    }

    public function simpan(Request $request)
    {
        $tahun = $request->tahun;

        try {
            $request->validate([
                'tahun' => 'required|digits:4|integer',
            ]);

            $jembatan_kodes = Penilaian::filterTahun($tahun)
                ->select('jembatan_kode')
                ->distinct()
                ->pluck('jembatan_kode');

            $jembatan = Jembatan::whereIn('kode_jembatan', $jembatan_kodes)->get();
            $komponen = Komponen::all();

            $nilai = [];
            foreach ($jembatan as $j) {
                $nilai[$j->id] = [];
                foreach ($komponen as $k) {
                    $nilaiPenilaian = Penilaian::where('jembatan_kode', $j->kode_jembatan)
                        ->where('komponen_kode', $k->kode_komponen)
                        ->filterTahun($tahun)
                        ->value('nilai');

                    $nilai[$j->id][$k->id] = $nilaiPenilaian !== null ? (int)$nilaiPenilaian : 0;
                }
            }

            if ($jembatan->isEmpty() || $komponen->isEmpty()) {
                Alert::error('Error', 'Data jembatan atau komponen tidak ditemukan untuk tahun ' . $tahun);
                return redirect()->back();
            }

            // Normalisasi Matriks
            $norm_matrix = [];
            foreach ($komponen as $k) {
                $sum_square = 0;
                foreach ($nilai as $j_id => $vals) {
                    $sum_square += pow($vals[$k->id], 2);
                }
                $sqrt_sum = sqrt($sum_square);

                foreach ($jembatan as $j) {
                    $norm_matrix[$j->id][$k->id] = $sqrt_sum == 0 ? 0 : $nilai[$j->id][$k->id] / $sqrt_sum;
                }
            }

            // Matriks Terbobot
            $weighted_matrix = [];
            $total_bobot = $komponen->sum('bobot');

            foreach ($jembatan as $j) {
                foreach ($komponen as $k) {
                    $bobot_normal = $k->bobot / $total_bobot;
                    $weighted_matrix[$j->id][$k->id] = $norm_matrix[$j->id][$k->id] * $bobot_normal;
                }
            }

            // Solusi ideal positif & negatif
            $A_pos = [];
            $A_neg = [];
            foreach ($komponen as $k) {
                $column_values = array_column($weighted_matrix, $k->id);
                $A_pos[$k->id] = ($k->tipe == 'benefit') ? max($column_values) : min($column_values);
                $A_neg[$k->id] = ($k->tipe == 'benefit') ? min($column_values) : max($column_values);
            }

            // Jarak ke solusi ideal
            $D_pos = [];
            $D_neg = [];
            foreach ($jembatan as $j) {
                $D_pos[$j->id] = sqrt(array_sum(array_map(fn($x, $a) => pow($x - $a, 2), $weighted_matrix[$j->id], $A_pos)));
                $D_neg[$j->id] = sqrt(array_sum(array_map(fn($x, $a) => pow($x - $a, 2), $weighted_matrix[$j->id], $A_neg)));
            }

            // Skor preferensi
            $P = [];
            foreach ($jembatan as $j) {
                $denominator = $D_pos[$j->id] + $D_neg[$j->id];
                $P[$j->id] = $denominator == 0 ? 0 : $D_neg[$j->id] / $denominator;
            }

            arsort($P);

            // Hapus data lama
            Laporan::where('tahun', $tahun)->delete();

            // Simpan data baru
            foreach ($P as $jembatan_id => $skor) {
                $jembatanItem = $jembatan->firstWhere('id', $jembatan_id);
                if ($jembatanItem) {
                    Laporan::create([
                        'nama_jembatan' => $jembatanItem->nama_jembatan,
                        'desa_satu' => $jembatanItem->desa_satu->nama_desa,
                        'desa_dua' => $jembatanItem->desa_dua->nama_desa,
                        'preferensi' => $skor,
                        'tahun' => $tahun,
                    ]);
                }
            }

            Alert::success('Berhasil', 'Data laporan berhasil disimpan');
            return redirect()->route('laporan');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data laporan');
            return redirect()->back();
        }
    }
}
