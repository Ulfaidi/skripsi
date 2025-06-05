@extends('template-admin.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <!-- Header Card -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <span class="text-muted fw-light">Data Wilayah /</span> Kecamatan
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahKecamatanModal">
                            <i class="bx bx-plus me-1"></i>
                            Tambah Kecamatan
                        </button>
                    </div>
            <div class="card-body">
                <!-- Search Bar -->
                <div class="mb-3">
                    <div class="input-group w-25 ms-auto">
                        <span class="input-group-text">
                            <i class="bx bx-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama kecamatan...">
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="kecamatanTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Kecamatan</th>
                                <th>Nama Kecamatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kecamatans as $kecamatan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kecamatan->kode_kecamatan }}</td>
                                    <td>{{ $kecamatan->nama_kecamatan }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-warning btn-sm edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#editKecamatanModal"
                                                data-id="{{ $kecamatan->id }}" data-kode="{{ $kecamatan->kode_kecamatan }}"
                                                data-nama="{{ $kecamatan->nama_kecamatan }}" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn"
                                                data-id="{{ $kecamatan->id }}" title="Hapus">
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

    <!-- Modal Tambah Kecamatan -->
    <div class="modal fade" id="tambahKecamatanModal" tabindex="-1" aria-labelledby="tambahKecamatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKecamatanModalLabel">Tambah Kecamatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kecamatan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_kecamatan" class="form-label">Kode Kecamatan</label>
                            <input type="text" class="form-control" id="kode_kecamatan" name="kode_kecamatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kecamatan" class="form-label">Nama Kecamatan</label>
                            <input type="text" class="form-control" id="nama_kecamatan" name="nama_kecamatan" required>
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

    <!-- Modal Edit Kecamatan -->
    <div class="modal fade" id="editKecamatanModal" tabindex="-1" aria-labelledby="editKecamatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKecamatanModalLabel">Edit Kecamatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKecamatanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_kode_kecamatan" class="form-label">Kode Kecamatan</label>
                            <input type="text" class="form-control" id="edit_kode_kecamatan" name="kode_kecamatan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_kecamatan" class="form-label">Nama Kecamatan</label>
                            <input type="text" class="form-control" id="edit_nama_kecamatan" name="nama_kecamatan"
                                required>
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
            const table = document.getElementById('kecamatanTable');
            const rows = table.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchText = searchInput.value.toLowerCase();

                for (let i = 1; i < rows.length; i++) {
                    const namaKecamatan = rows[i].getElementsByTagName('td')[
                        2]; // Index 2 untuk kolom nama kecamatan
                    if (namaKecamatan) {
                        const text = namaKecamatan.textContent || namaKecamatan.innerText;
                        if (text.toLowerCase().indexOf(searchText) > -1) {
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

                    const form = document.getElementById('editKecamatanForm');
                    form.action = `/kecamatan/${id}`;

                    document.getElementById('edit_kode_kecamatan').value = kode;
                    document.getElementById('edit_nama_kecamatan').value = nama;
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
                            // Buat form untuk mengirim request DELETE
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/kecamatan/${id}`;

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

            // Reset form modal saat ditutup
            const tambahModal = document.getElementById('tambahKecamatanModal');
            tambahModal.addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
            });
        });
    </script>
@endsection
