@extends('template-admin.layout')

@section('content')
    {{-- Loading Overlay --}}
    <div id="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-primary">Memproses Perhitungan...</p>
        </div>
    </div>

    <style>
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            text-align: center;
        }

        .loading-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .filter-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .filter-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .filter-select {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        }

        .btn-hitung {
            border-radius: 10px;
            padding: 8px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
        }

        .btn-hitung:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }

        .btn-hitung:disabled {
            background: linear-gradient(135deg, #a0a0a0 0%, #808080 100%);
            transform: none;
            box-shadow: none;
        }
    </style>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card filter-card mb-4">
                    <div class="card-body p-4">
                        <form action="{{ route('perhitungan') }}" method="GET" class="form-inline" id="formPerhitungan">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <div class="flex-grow-1">
                                    <select name="tahun" id="tahun" class="form-control filter-select">
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($tahun_list as $t)
                                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                                                {{ $t }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-hitung" id="btnHitung">
                                    <i class="fas fa-calculator me-1"></i> Hitung
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($tahun)
            {{-- Bobot dan Bobot Normalisasi --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Bobot dan Bobot Normalisasi</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Komponen</th>
                                    <th>Nama Komponen</th>
                                    <th>Bobot</th>
                                    <th>Bobot Normalisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_bobot = $komponen->sum('bobot');
                                @endphp
                                @foreach ($komponen as $k)
                                    <tr>
                                        <td>{{ $k->kode_komponen }}</td>
                                        <td>{{ $k->nama_komponen }}</td>
                                        <td class="text-end">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format($k->bobot, 2, ',', '.'))) }}
                                        </td>
                                        <td class="text-end">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format(($k->bobot / $total_bobot) * 100, 1, ',', '.'))) }}%
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary">
                                    <td colspan="2" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ number_format($total_bobot, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end"><strong>100%</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Matriks Penilaian Awal --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Matriks Penilaian Awal</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jembatan</th>
                                    @foreach ($komponen as $k)
                                        <th>{{ $k->kode_komponen }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jembatan as $j)
                                    <tr>
                                        <td>{{ $j->nama_jembatan }}</td>
                                        @foreach ($komponen as $k)
                                            <td class="text-end">
                                                {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format($nilai[$j->id][$k->id], 2, ',', '.'))) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Matriks Normalisasi --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Matriks Normalisasi</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jembatan</th>
                                    @foreach ($komponen as $k)
                                        <th>{{ $k->kode_komponen }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jembatan as $j)
                                    <tr>
                                        <td>{{ $j->nama_jembatan }}</td>
                                        @foreach ($komponen as $k)
                                            <td class="text-end">
                                                {{ number_format($norm_matrix[$j->id][$k->id], 4, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Matriks Terbobot --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Matriks Terbobot</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jembatan</th>
                                    @foreach ($komponen as $k)
                                        <th>{{ $k->kode_komponen }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jembatan as $j)
                                    <tr>
                                        <td>{{ $j->nama_jembatan }}</td>
                                        @foreach ($komponen as $k)
                                            <td class="text-end">
                                                {{ number_format($weighted_matrix[$j->id][$k->id], 4, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Solusi Ideal --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Solusi Ideal Positif dan Negatif</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Komponen</th>
                                    @foreach ($komponen as $k)
                                        <th>{{ $k->kode_komponen }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Solusi Ideal Positif (A+)</strong></td>
                                    @foreach ($komponen as $k)
                                        <td class="text-end">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format($A_pos[$k->id], 4, ',', '.'))) }}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Solusi Ideal Negatif (A-)</strong></td>
                                    @foreach ($komponen as $k)
                                        <td class="text-end">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format($A_neg[$k->id], 4, ',', '.'))) }}
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Jarak ke Solusi Ideal --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Jarak ke Solusi Ideal</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jembatan</th>
                                    <th>D+</th>
                                    <th>D-</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jembatan as $j)
                                    <tr>
                                        <td>{{ $j->nama_jembatan }}</td>
                                        <td class="text-end">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format($D_pos[$j->id], 4, ',', '.'))) }}
                                        </td>
                                        <td class="text-end">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format($D_neg[$j->id], 4, ',', '.'))) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Skor Preferensi & Peringkat --}}
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Skor Preferensi dan Peringkat</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <form action="{{ route('perhitungan.simpan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jembatan</th>
                                        <th>Skor Preferensi</th>
                                        <th>Peringkat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ranking as $nama => $score)
                                        <tr>
                                            <td>{{ $nama }}</td>
                                            <td class="text-end">
                                                {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format(1 - $score, 4, ',', '.'))) }}
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-end mt-3">
                                @php
                                    $laporanExists = App\Models\Laporan::where('tahun', $tahun)->exists();
                                    $penilaianTerakhir = App\Models\Penilaian::where('tahun', $tahun)
                                        ->orderBy('updated_at', 'desc')
                                        ->first();
                                    $laporanTerakhir = App\Models\Laporan::where('tahun', $tahun)
                                        ->orderBy('updated_at', 'desc')
                                        ->first();

                                    $adaPerubahan = false;
                                    if ($laporanExists && $penilaianTerakhir && $laporanTerakhir) {
                                        $adaPerubahan = $penilaianTerakhir->updated_at > $laporanTerakhir->updated_at;

                                        // Cek perubahan nama jembatan
                                        $jembatanLaporan = App\Models\Laporan::where('tahun', $tahun)
                                            ->pluck('nama_jembatan')
                                            ->toArray();
                                        $jembatanSekarang = $jembatan->pluck('nama_jembatan')->toArray();

                                        if (
                                            count(array_diff($jembatanLaporan, $jembatanSekarang)) > 0 ||
                                            count(array_diff($jembatanSekarang, $jembatanLaporan)) > 0
                                        ) {
                                            $adaPerubahan = true;
                                        }
                                    }
                                @endphp

                                @if (!$laporanExists || $adaPerubahan)
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        @if ($laporanExists)
                                            Perbarui Laporan
                                        @else
                                            Simpan ke Laporan
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('formPerhitungan').addEventListener('submit', function(e) {
            const tahun = document.getElementById('tahun').value;
            if (tahun) {
                document.getElementById('loading-overlay').style.display = 'flex';
                document.getElementById('btnHitung').disabled = true;
            }
        });
    </script>
@endsection
