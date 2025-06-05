@extends('template-admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Laporan Data Jembatan per Tahun</h4>
            </div>
            <div class="card-body">
                {{-- <h5 class="card-title text-end">Pencarian Data Jembatan</h5> --}}
                <div class="input-group w-25 ms-auto">
                    <span class="input-group-text">
                        <i class="bx bx-search"></i>
                    </span>
                    <input type="text" id="searchTahun" class="form-control" placeholder="Cari berdasarkan tahun...">
                </div>
            </div>
        </div>
        @foreach ($data as $tahun => $laporans)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Data Jembatan Tahun {{ $tahun }}</span>
                    <a href="{{ route('laporan.cetak', $tahun) }}" class="btn btn-primary btn-sm" target="_blank">Cetak
                        Laporan</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jembatan</th>
                                    <th>Kecamatan</th>
                                    <th>Desa</th>
                                    <th>Skor</th>
                                    <th>Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $key => $l)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $l->nama_jembatan }}</td>
                                        <td>{{ $l->kecamatan }}</td>
                                        <td>{{ $l->desa }}</td>
                                        <td class="text-center">
                                            {{ preg_replace('/^(\d+),0+$/', '$1', preg_replace('/,?0+$/', '', number_format(1 - $l->preferensi, 4, ',', '.'))) }}
                                        </td>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTahun');
            const cards = document.querySelectorAll('.card');

            searchInput.addEventListener('keyup', function() {
                const searchValues = this.value.split(',').map(value => value.trim().toLowerCase());

                cards.forEach(card => {
                    if (card.querySelector('.card-header span')) {
                        const tahunText = card.querySelector('.card-header span').textContent
                            .toLowerCase();
                        const tahunValue = tahunText.match(
                            /\d+/); // Mengambil angka tahun dari teks

                        if (tahunValue && searchValues.some(search => tahunValue[0].includes(
                                search))) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
@endsection
