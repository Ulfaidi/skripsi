<!DOCTYPE html>
<html>

<head>
    {{-- <title>Cetak Laporan Jembatan Tahun {{ $tahun }}</title> --}}
    <style>
        /* CSS untuk cetak */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .text-center {
            text-align: center;
        }

        .kop {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }

        .kop img {
            width: 80px;
        }

        .kop h2,
        .kop h3 {
            margin: 5px 0;
        }

        .ttd {
            float: right;
            width: 250px;
            margin-top: 30px;
            text-align: center;
        }

        @media print {
            @page {
                margin: 0;
                size: auto;
            }

            body {
                margin: 1.6cm;
            }
        }
    </style>
</head>

<body>
    <div class="kop">
        <div style="display: flex; align-items: center;">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="width: 60px; margin-right: 20px;">
            <div>
                <h2 style="margin: 0;">PEMERINTAH KABUPATEN KUANTAN SINGINGI</h2>
                <h3 style="margin: 5px 0;">DINAS PEKERJAAN UMUM DAN PENATAAN RUANG</h3>
                <h4 style="margin: 5px 0;">Jl. Achmad Yani, Sungai Jering, Kec. Kuantan Tengah, Kab. Kuantan Singingi
                </h4>
            </div>
        </div>
    </div>

    <h3>Laporan Data Jembatan Tahun {{ $tahun }}</h3>
    <table>
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
            @foreach ($laporan as $key => $l)
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

    <div class="ttd">
        <p>Teluk Kuantan, {{ date('d F Y') }}</p>
        <p>Kepala Dinas PUPR</p>
        <br><br><br>
        <p><u>Nama Kepala Dinas</u></p>
        <p>NIP. .........................</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            }
        }
    </script>
</body>

</html>
