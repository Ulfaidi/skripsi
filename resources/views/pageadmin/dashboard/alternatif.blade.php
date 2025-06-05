@extends('template-admin.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Alternatif dan Kriteria</h5>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="alternatif-tab" data-bs-toggle="tab"
                                    data-bs-target="#alternatif" type="button" role="tab" aria-controls="alternatif"
                                    aria-selected="true">Alternatif (Jembatan)</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kriteria-tab" data-bs-toggle="tab" data-bs-target="#kriteria"
                                    type="button" role="tab" aria-controls="kriteria" aria-selected="false">Kriteria
                                    (Komponen)</button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Alternatif Tab -->
                            <div class="tab-pane fade show active" id="alternatif" role="tabpanel"
                                aria-labelledby="alternatif-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Jembatan</th>
                                                <th>Nama Jembatan</th>
                                                <th>Kecamatan</th>
                                                <th>Desa</th>
                                                <th>Tahun Pembangunan</th>
                                                <th>Panjang (m)</th>
                                                <th>Lebar (m)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($alternatifData as $key => $jembatan)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $jembatan->kode_jembatan }}</td>
                                                    <td>{{ $jembatan->nama_jembatan }}</td>
                                                    <td>{{ $jembatan->kecamatan->nama_kecamatan }}</td>
                                                    <td>{{ $jembatan->desa->nama_desa }}</td>
                                                    <td>{{ $jembatan->tahun_pembangunan }}</td>
                                                    <td>{{ $jembatan->panjang }}</td>
                                                    <td>{{ $jembatan->lebar }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Kriteria Tab -->
                            <div class="tab-pane fade" id="kriteria" role="tabpanel" aria-labelledby="kriteria-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Komponen</th>
                                                <th>Nama Komponen</th>
                                                <th>Bobot</th>
                                                <th>Tipe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kriteriaData as $key => $komponen)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $komponen->kode_komponen }}</td>
                                                    <td>{{ $komponen->nama_komponen }}</td>
                                                    <td>{{ $komponen->bobot }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $komponen->tipe == 'benefit' ? 'success' : 'danger' }}">
                                                            {{ $komponen->tipe }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Inisialisasi DataTable
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
                }
            });
        });
    </script>
@endpush
