@extends('template-admin.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <!-- Header Card -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <span class="text-muted fw-light">Data Penilaian /</span> Jembatan
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahPenilaianModal">
                            <i class="bx bx-plus me-1"></i>
                            Tambah Penilaian
                        </button>
                    </div>

                    <!-- Body Card -->
                    <div class="card-body">
                        <!-- Search Bar -->
                        <div class="mb-3">
                            <div class="input-group w-25 ms-auto">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Cari nama jembatan...">
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="penilaianTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2">Nama Jembatan</th>
                                        <th class="text-center align-middle" rowspan="2">Tahun</th>
                                        <th class="text-center" colspan="{{ count($komponen) }}">Nilai Komponen</th>
                                        <th class="text-center align-middle" rowspan="2" style="width: 100px">Aksi</th>
                                    </tr>
                                    <tr>
                                        @foreach ($komponen as $k)
                                            <th class="text-center">{{ $k->kode_komponen }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $groupedPenilaian = $penilaian->groupBy(['jembatan_kode', 'tahun']);
                                    @endphp
                                    @foreach ($groupedPenilaian as $jembatanKode => $tahunGroup)
                                        @foreach ($tahunGroup as $tahun => $penilaianGroup)
                                            @php
                                                $firstPenilaian = $penilaianGroup->first();
                                                $jembatanNama = $firstPenilaian?->jembatan?->nama_jembatan ?? '-';
                                            @endphp
                                            <tr>
                                                <td>{{ $jembatanNama }}</td>
                                                <td class="text-center">{{ $tahun }}</td>
                                                @foreach ($komponen as $k)
                                                    @php
                                                        $nilai = $penilaianGroup->where('komponen_kode', $k->kode_komponen)->first();
                                                    @endphp
                                                    <td class="text-center">{{ $nilai ? $nilai->nilai : '-' }}</td>
                                                @endforeach
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button type="button"
                                                            class="btn btn-outline-warning btn-sm edit-btn"
                                                            data-bs-toggle="modal" data-bs-target="#editPenilaianModal"
                                                            data-jembatan-kode="{{ $firstPenilaian->jembatan_kode ?? '' }}"
                                                            data-tahun="{{ $tahun }}"
                                                            data-nilai='@json($penilaianGroup->pluck("nilai", "komponen_kode"))' title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-sm delete-btn"
                                                            data-jembatan-kode="{{ $firstPenilaian->jembatan_kode ?? '' }}"
                                                            data-tahun="{{ $tahun }}" title="Hapus">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Penilaian -->
    <div class="modal fade" id="tambahPenilaianModal" tabindex="-1" aria-labelledby="tambahPenilaianModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPenilaianModalLabel">Tambah Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('penilaian.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jembatan_id" class="form-label">Jembatan</label>
                                <select class="form-select" id="jembatan_id" name="jembatan_id" required>
                                    <option value="">Pilih Jembatan</option>
                                    @foreach ($jembatan as $j)
                                        <option value="{{ $j->id }}">{{ $j->nama_jembatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" required
                                    min="2000" max="2100">
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($komponen as $k)
                                <div class="col-md-6 mb-3">
                                    <label for="komponen_{{ $k->id }}"
                                        class="form-label">{{ $k->nama_komponen }}</label>
                                    <input type="number" class="form-control" id="komponen_{{ $k->id }}"
                                        name="komponen[{{ $k->id }}]" required min="1" max="7">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Penilaian -->
    <div class="modal fade" id="editPenilaianModal" tabindex="-1" aria-labelledby="editPenilaianModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPenilaianModalLabel">Edit Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPenilaianForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($komponen as $k)
                                <div class="col-md-6 mb-3">
                                    <label for="edit_komponen_{{ $k->kode_komponen }}"
                                        class="form-label">{{ $k->nama_komponen }}</label>
                                    <input type="number" class="form-control"
                                        id="edit_komponen_{{ $k->kode_komponen }}"
                                        name="komponen[{{ $k->kode_komponen }}]" required min="1" max="7">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('penilaianTable');
            const rows = table.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchText = searchInput.value.toLowerCase();

                for (let i = 2; i < rows.length; i++) {
                    const namaJembatan = rows[i].getElementsByTagName('td')[0];
                    if (namaJembatan) {
                        const text = namaJembatan.textContent || namaJembatan.innerText;
                        if (text.toLowerCase().indexOf(searchText) > -1) {
                            rows[i].style.display = '';
                        } else {
                            rows[i].style.display = 'none';
                        }
                    }
                }
            });

            // Reset form modal saat ditutup
            const tambahModal = document.getElementById('tambahPenilaianModal');
            tambahModal.addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
            });

            // Handle edit button click
            const editModal = document.getElementById('editPenilaianModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const jembatanKode = button.getAttribute('data-jembatan-kode');
                const tahun = button.getAttribute('data-tahun');
                const nilai = JSON.parse(button.getAttribute('data-nilai'));

                const form = this.querySelector('#editPenilaianForm');
                form.action = `/penilaian/${jembatanKode}/${tahun}`;

                // Populate form with existing values
                Object.keys(nilai).forEach(komponenKode => {
                    const input = form.querySelector(`#edit_komponen_${komponenKode}`);
                    if (input) input.value = nilai[komponenKode];
                });
            });

            // Handle delete button click
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jembatanKode = this.getAttribute('data-jembatan-kode');
                    const tahun = this.getAttribute('data-tahun');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Buat form untuk mengirim request DELETE
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/penilaian/${jembatanKode}/${tahun}`;

                            // Tambahkan CSRF token
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            // Tambahkan method DELETE
                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';
                            form.appendChild(methodField);

                            // Tambahkan form ke body dan submit
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
