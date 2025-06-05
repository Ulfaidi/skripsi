@extends('template-admin.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <!-- Header Card -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <span class="text-muted fw-light">Data Jembatan /</span> Komponen
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahKomponenModal">
                            <i class="bx bx-plus me-1"></i>
                            Tambah Komponen
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
                                    placeholder="Cari nama komponen...">
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="komponenTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kode Komponen</th>
                                        <th class="text-center">Nama Komponen</th>
                                        <th class="text-center">Bobot</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center" style="width: 100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($komponens as $komponen)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $komponen->kode_komponen }}</td>
                                            <td>{{ $komponen->nama_komponen }}</td>
                                            <td class="text-center">{{ $komponen->bobot }}</td>
                                            <td class="text-center">{{ $komponen->tipe }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-warning btn-sm edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editKomponenModal"
                                                        data-id="{{ $komponen->id }}"
                                                        data-kode="{{ $komponen->kode_komponen }}"
                                                        data-nama="{{ $komponen->nama_komponen }}"
                                                        data-bobot="{{ $komponen->bobot }}"
                                                        data-tipe="{{ $komponen->tipe }}" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm delete-btn"
                                                        data-id="{{ $komponen->id }}" title="Hapus">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
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

    <!-- Modal Tambah Komponen -->
    <div class="modal fade" id="tambahKomponenModal" tabindex="-1" aria-labelledby="tambahKomponenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKomponenModalLabel">Tambah Komponen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('komponen.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_komponen" class="form-label">Kode Komponen</label>
                            <input type="text" class="form-control" id="kode_komponen" name="kode_komponen" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_komponen" class="form-label">Nama Komponen</label>
                            <input type="text" class="form-control" id="nama_komponen" name="nama_komponen" required>
                        </div>
                        <div class="mb-3">
                            <label for="bobot" class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" id="bobot" name="bobot"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <select class="form-select" id="tipe" name="tipe" required>
                                <option value="">Pilih Tipe</option>
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
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

    <!-- Modal Edit Komponen -->
    <div class="modal fade" id="editKomponenModal" tabindex="-1" aria-labelledby="editKomponenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKomponenModalLabel">Edit Komponen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKomponenForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_kode_komponen" class="form-label">Kode Komponen</label>
                            <input type="text" class="form-control" id="edit_kode_komponen" name="kode_komponen"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_komponen" class="form-label">Nama Komponen</label>
                            <input type="text" class="form-control" id="edit_nama_komponen" name="nama_komponen"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bobot" class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" id="edit_bobot" name="bobot"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tipe" class="form-label">Tipe</label>
                            <select class="form-select" id="edit_tipe" name="tipe" required>
                                <option value="">Pilih Tipe</option>
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
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
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('komponenTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = tbody.getElementsByTagName('tr');

            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    const namaKomponen = rows[i].getElementsByTagName('td')[2];
                    const kodeKomponen = rows[i].getElementsByTagName('td')[1];

                    if (namaKomponen && kodeKomponen) {
                        const namaText = namaKomponen.textContent || namaKomponen.innerText;
                        const kodeText = kodeKomponen.textContent || kodeKomponen.innerText;

                        if (namaText.toLowerCase().includes(searchText) || kodeText.toLowerCase().includes(
                                searchText)) {
                            rows[i].style.display = '';
                        } else {
                            rows[i].style.display = 'none';
                        }
                    }
                }
            });

            // Handle edit button click
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const kode = this.getAttribute('data-kode');
                    const nama = this.getAttribute('data-nama');
                    const bobot = this.getAttribute('data-bobot');
                    const tipe = this.getAttribute('data-tipe');

                    const form = document.getElementById('editKomponenForm');
                    form.action = `/komponen/${id}`;

                    document.getElementById('edit_kode_komponen').value = kode;
                    document.getElementById('edit_nama_komponen').value = nama;
                    document.getElementById('edit_bobot').value = bobot;
                    document.getElementById('edit_tipe').value = tipe;
                });
            });

            // Handle delete button click
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');

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
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/komponen/${id}`;

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';
                            form.appendChild(methodField);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // Reset form modal saat ditutup
            const tambahModal = document.getElementById('tambahKomponenModal');
            tambahModal.addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
            });

            // Reset form edit modal saat ditutup
            const editModal = document.getElementById('editKomponenModal');
            editModal.addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
            });
        });
    </script>
@endsection
