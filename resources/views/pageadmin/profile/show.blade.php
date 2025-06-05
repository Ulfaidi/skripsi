@extends('template-admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-circle mr-2"></i>Profile Admin
                        </h3>
                    </div>
                    <div class="card-body">
                        @if (!$user->name || !$user->no_hp || !$user->alamat)
                            <script>
                                Swal.fire({
                                    title: 'Perhatian!',
                                    text: 'Mohon lengkapi data profile Anda',
                                    icon: 'warning',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Mengerti'
                                });
                            </script>
                        @endif

                        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                            id="profileForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="profile-image-container">
                                        @if ($user->foto)
                                            <img src="{{ asset($user->foto) }}" alt="Profile Photo"
                                                class="img-thumbnail rounded-circle profile-image mb-2">
                                        @else
                                            <img src="{{ asset('assets/img/default-avatar.png') }}" alt="Default Avatar"
                                                class="img-thumbnail rounded-circle profile-image mb-2">
                                        @endif
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('foto') is-invalid @enderror" id="foto"
                                                name="foto" accept="image/*">
                                            <label class="btn btn-outline-primary btn-sm" for="foto">
                                                <i class="fas fa-camera"></i> Ganti Foto
                                            </label>
                                            @error('foto')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">Nama <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-user text-primary"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $user->name) }}"
                                                required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if (!$user->name)
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-circle"></i> Nama belum diisi
                                            </small>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="no_hp">Nomor HP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-phone text-primary"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                                id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                                required>
                                            @error('no_hp')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if (!$user->no_hp)
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-circle"></i> Nomor HP belum diisi
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                        </span>
                                    </div>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                        required>{{ old('alamat', $user->alamat) }}</textarea>
                                    @error('alamat')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!$user->alamat)
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-circle"></i> Alamat belum diisi
                                    </small>
                                @endif
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Ubah Password</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-lock text-primary"></i>
                                                </span>
                                            </div>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password">
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-lock text-primary"></i>
                                                </span>
                                            </div>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-image-container {
            position: relative;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .custom-file {
            position: relative;
            display: inline-block;
        }

        .custom-file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .input-group-text {
            border: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #80bdff;
        }

        .btn-primary {
            padding: 8px 20px;
        }
    </style>

    <script>
        // Preview foto sebelum upload
        document.getElementById('foto').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-image').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Konfirmasi sebelum submit form
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Update',
                text: "Apakah Anda yakin ingin memperbarui profile?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{{ session('title') }}',
                    text: '{{ session('message') }}',
                    icon: '{{ session('icon') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
@endsection
