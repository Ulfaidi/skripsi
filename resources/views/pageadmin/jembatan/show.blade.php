@extends('template-admin.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <span class="text-muted fw-light">Data Jembatan /</span> Jembatan
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#tambahJembatanModal">
                            <i class="bx bx-plus me-1"></i>
                            Tambah Jembatan
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group w-25 ms-auto">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Cari nama jembatan...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="jembatanTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Jembatan</th>
                                        <th>Lokasi</th>
                                        <th class="text-center">Tahun</th>
                                        <th class="text-center">Panjang</th>
                                        <th class="text-center">Lebar</th>
                                        <th class="text-center">Foto</th>
                                        <th class="text-center" style="width: 100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jembatans as $jembatan)
                                        <tr>
                                            <td>{{ $jembatan->kode_jembatan }}</td>
                                            <td>{{ $jembatan->nama_jembatan }}</td>
                                            <td>
                                                <small>
                                                    {{-- {{ $jembatan->kecamatan_satu->nama_kecamatan }} --}}
                                                    {{ $jembatan->desa_satu->nama_desa }}
                                                    -
                                                    {{-- {{ $jembatan->kecamatan_dua->nama_kecamatan }} --}}
                                                    {{ $jembatan->desa_dua->nama_desa }}
                                                </small>
                                            </td>
                                            <td class="text-center">{{ $jembatan->tahun_pembangunan }}</td>
                                            <td class="text-center">{{ number_format($jembatan->panjang, 2) }}</td>
                                            <td class="text-center">{{ number_format($jembatan->lebar, 2) }}</td>
                                            <td class="text-center">
                                                @if ($jembatan->foto)
                                                    <img src="{{ asset('uploads/jembatan/' . $jembatan->foto) }}"
                                                        alt="Foto {{ $jembatan->nama_jembatan }}" class="img-thumbnail"
                                                        style="max-width: 100px; max-height: 100px; cursor: pointer;"
                                                        onclick="showImage(this.src)">
                                                @else
                                                    <span class="text-muted">Tidak ada foto</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-warning btn-sm edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editJembatanModal"
                                                        data-id="{{ $jembatan->id }}"
                                                        data-kode="{{ $jembatan->kode_jembatan }}"
                                                        data-nama="{{ $jembatan->nama_jembatan }}"
                                                        data-kecamatan_satu_kode="{{ $jembatan->kecamatan_satu_kode }}"
                                                        data-kecamatan_dua_kode="{{ $jembatan->kecamatan_dua_kode }}"
                                                        data-desa_satu_kode="{{ $jembatan->desa_satu_kode }}"
                                                        data-desa_dua_kode="{{ $jembatan->desa_dua_kode }}"
                                                        data-tahun="{{ $jembatan->tahun_pembangunan }}"
                                                        data-panjang="{{ $jembatan->panjang }}"
                                                        data-lebar="{{ $jembatan->lebar }}"
                                                        data-foto="{{ $jembatan->foto }}" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <form action="{{ route('jembatan.destroy', $jembatan->id) }}"
                                                        method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
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

    <div class="modal fade" id="tambahJembatanModal" tabindex="-1" aria-labelledby="tambahJembatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahJembatanModalLabel">Tambah Jembatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('jembatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_jembatan" class="form-label">Kode Jembatan</label>
                            <input type="text" class="form-control" id="kode_jembatan" name="kode_jembatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_jembatan" class="form-label">Nama Jembatan</label>
                            <input type="text" class="form-control" id="nama_jembatan" name="nama_jembatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kecamatan_satu_kode" class="form-label">Dari Kecamatan</label>
                            <select class="form-select" id="kecamatan_satu_kode" name="kecamatan_satu_kode" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->kode_kecamatan }}">{{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="desa_satu_kode" class="form-label">Dari Desa</label>
                            <select class="form-select" id="desa_satu_kode" name="desa_satu_kode" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kecamatan_dua_kode" class="form-label">Ke Kecamatan</label>
                            <select class="form-select" id="kecamatan_dua_kode" name="kecamatan_dua_kode" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->kode_kecamatan }}">{{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="desa_dua_kode" class="form-label">Ke Desa</label>
                            <select class="form-select" id="desa_dua_kode" name="desa_dua_kode" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_pembangunan" class="form-label">Tahun Pembangunan</label>
                            <input type="number" class="form-control" id="tahun_pembangunan" name="tahun_pembangunan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="panjang" class="form-label">Panjang (meter)</label>
                            <input type="number" step="0.01" class="form-control" id="panjang" name="panjang"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="lebar" class="form-label">Lebar (meter)</label>
                            <input type="number" step="0.01" class="form-control" id="lebar" name="lebar"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto">
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

    <div class="modal fade" id="editJembatanModal" tabindex="-1" aria-labelledby="editJembatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editJembatanModalLabel">Edit Jembatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editJembatanForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_kode_jembatan" class="form-label">Kode Jembatan</label>
                                    <input type="text" class="form-control" id="edit_kode_jembatan"
                                        name="kode_jembatan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_nama_jembatan" class="form-label">Nama Jembatan</label>
                                    <input type="text" class="form-control" id="edit_nama_jembatan"
                                        name="nama_jembatan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_kecamatan_satu_kode" class="form-label">Dari Kecamatan</label>
                                    <select class="form-select" id="edit_kecamatan_satu_kode" name="kecamatan_satu_kode"
                                        required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->kode_kecamatan }}">
                                                {{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_desa_satu_kode" class="form-label">Dari Desa</label>
                                    <select class="form-select" id="edit_desa_satu_kode" name="desa_satu_kode" required>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_kecamatan_dua_kode" class="form-label">Ke Kecamatan</label>
                                    <select class="form-select" id="edit_kecamatan_dua_kode" name="kecamatan_dua_kode"
                                        required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->kode_kecamatan }}">
                                                {{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_desa_dua_kode" class="form-label">Ke Desa</label>
                                    <select class="form-select" id="edit_desa_dua_kode" name="desa_dua_kode" required>
                                        <option value="">Pilih Desa</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_tahun_pembangunan" class="form-label">Tahun Pembangunan</label>
                                    <input type="number" class="form-control" id="edit_tahun_pembangunan"
                                        name="tahun_pembangunan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_panjang" class="form-label">Panjang (meter)</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_panjang"
                                        name="panjang" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_lebar" class="form-label">Lebar (meter)</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_lebar"
                                        name="lebar" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="edit_foto" name="foto"
                                        accept="image/*">

                                    <div id="edit_foto_preview" class="mt-2" style="display: none;">
                                        <img src="" alt="Preview" class="img-thumbnail"
                                            style="max-height: 200px;">
                                    </div>

                                    <div id="edit_current_foto" class="mt-2">
                                        <img src="" alt="Current Photo" class="img-thumbnail"
                                            style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>
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


    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Preview">
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('jembatanTable');
            const rows = table.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchText = searchInput.value.toLowerCase();

                for (let i = 1; i < rows.length; i++) { // Start from 1, not 2, to skip thead
                    const namaJembatan = rows[i].getElementsByTagName('td')[1];
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

            // Function to load desas based on kecamatan
            async function loadDesas(kecamatanKode, targetSelectId, selectedDesa = '') {
                const desaSelect = document.getElementById(targetSelectId);
                desaSelect.innerHTML = '<option value="">Pilih Desa</option>'; // Clear existing options

                if (kecamatanKode) {
                    try {
                        const response = await fetch(`/desas/${kecamatanKode}`);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const data = await response.json();
                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.kode_desa;
                            option.textContent = desa.nama_desa;
                            if (desa.kode_desa === selectedDesa) {
                                option.selected = true;
                            }
                            desaSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error('Error loading desas:', error);
                        desaSelect.innerHTML = '<option value="">Error loading desa</option>';
                    }
                }
            }

            // TAMBAH MODAL - Handle kecamatan changes
            document.getElementById('kecamatan_satu_kode').addEventListener('change', function() {
                loadDesas(this.value, 'desa_satu_kode');
            });

            document.getElementById('kecamatan_dua_kode').addEventListener('change', function() {
                loadDesas(this.value, 'desa_dua_kode');
            });

            // EDIT MODAL - Handle edit button click
            const editModal = document.getElementById('editJembatanModal');
            editModal.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const kode = button.getAttribute('data-kode');
                const nama = button.getAttribute('data-nama');
                const kecamatan_satu = button.getAttribute(
                    'data-kecamatan_satu_kode'); // Corrected attribute name
                const kecamatan_dua = button.getAttribute(
                    'data-kecamatan_dua_kode'); // Corrected attribute name
                const desa_satu = button.getAttribute(
                    'data-desa_satu_kode'); // Corrected attribute name
                const desa_dua = button.getAttribute('data-desa_dua_kode'); // Corrected attribute name
                const tahun = button.getAttribute('data-tahun');
                const panjang = button.getAttribute('data-panjang');
                const lebar = button.getAttribute('data-lebar');
                const foto = button.getAttribute('data-foto');

                // Set form action with ID
                const form = this.querySelector('#editJembatanForm');
                form.action = `/jembatan/${id}`;

                // Fill form fields
                document.getElementById('edit_kode_jembatan').value = kode;
                document.getElementById('edit_nama_jembatan').value = nama;
                document.getElementById('edit_tahun_pembangunan').value = tahun;
                document.getElementById('edit_panjang').value = panjang;
                document.getElementById('edit_lebar').value = lebar;

                // Set selected kecamatan values
                document.getElementById('edit_kecamatan_satu_kode').value = kecamatan_satu;
                document.getElementById('edit_kecamatan_dua_kode').value = kecamatan_dua;

                // Load desa options for kecamatan satu and set selected desa
                if (kecamatan_satu) {
                    await loadDesas(kecamatan_satu, 'edit_desa_satu_kode', desa_satu);
                }

                // Load desa options for kecamatan dua and set selected desa
                if (kecamatan_dua) {
                    await loadDesas(kecamatan_dua, 'edit_desa_dua_kode', desa_dua);
                }

                // Handle current photo display
                const currentFotoDiv = document.getElementById('edit_current_foto');
                const currentFotoImg = currentFotoDiv.querySelector('img');
                const fotoInput = document.getElementById('edit_foto'); // Get the file input itself

                if (foto) {
                    currentFotoImg.src =
                        `{{ asset('uploads/jembatan/') }}/${foto}`; // Use asset helper for full path
                    currentFotoDiv.style.display = 'block';
                } else {
                    currentFotoDiv.style.display = 'none';
                }

                // Reset preview foto and file input
                document.getElementById('edit_foto_preview').style.display = 'none';
                fotoInput.value = ''; // Clear the file input
            });

            // EDIT MODAL - Handle kecamatan changes (for when a user changes kecamatan in edit modal)
            document.getElementById('edit_kecamatan_satu_kode').addEventListener('change', function() {
                loadDesas(this.value, 'edit_desa_satu_kode');
            });

            document.getElementById('edit_kecamatan_dua_kode').addEventListener('change', function() {
                loadDesas(this.value, 'edit_desa_dua_kode');
            });

            // Preview foto saat memilih file baru for edit modal
            document.getElementById('edit_foto').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    const previewDiv = document.getElementById('edit_foto_preview');
                    const previewImg = previewDiv.querySelector('img');
                    const currentFotoDiv = document.getElementById('edit_current_foto');

                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewDiv.style.display = 'block';
                        currentFotoDiv.style.display =
                            'none'; // Hide current photo when new one is selected
                    }
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById('edit_foto_preview').style.display = 'none';
                    // If no new file is selected, show the current photo if it exists
                    const currentFotoSrc = document.getElementById('edit_current_foto').querySelector('img')
                        .src;
                    if (currentFotoSrc && currentFotoSrc !== window.location
                        .href) { // Check if src is not empty or current page URL
                        document.getElementById('edit_current_foto').style.display = 'block';
                    }
                }
            });

            // Reset form modal when closed
            const tambahModal = document.getElementById('tambahJembatanModal');
            tambahModal.addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
                document.getElementById('desa_satu_kode').innerHTML =
                    '<option value="">Pilih Desa</option>';
                document.getElementById('desa_dua_kode').innerHTML = '<option value="">Pilih Desa</option>';
            });

            // Reset edit modal when closed
            editModal.addEventListener('hidden.bs.modal', function() {
                // Clear and reset desa dropdowns
                document.getElementById('edit_desa_satu_kode').innerHTML =
                    '<option value="">Pilih Desa</option>';
                document.getElementById('edit_desa_dua_kode').innerHTML =
                    '<option value="">Pilih Desa</option>';

                // Hide image previews and clear file input
                document.getElementById('edit_foto_preview').style.display = 'none';
                document.getElementById('edit_foto_preview').querySelector('img').src = '';
                document.getElementById('edit_current_foto').style.display = 'none';
                document.getElementById('edit_current_foto').querySelector('img').src = '';
                document.getElementById('edit_foto').value = ''; // Clear the file input
            });

            // Function to show image in modal (global function)
            window.showImage = function(src) {
                const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                document.getElementById('modalImage').src = src;
                modal.show();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
