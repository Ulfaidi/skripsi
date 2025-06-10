@extends('template-admin.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <h5 class="card-title text-primary">Selamat Datang di Sistem SPK! 🎉</h5>
                            <p class="mb-4">
                                Sistem Pendukung Keputusan ini telah membantu dalam pengambilan keputusan dengan
                                <span class="fw-bold">{{ $statistik['total_penilaian'] }}</span> penilaian yang telah
                                dilakukan.
                            </p>
                            {{-- <a href="{{ route('alternatif') }}" class="btn btn-sm btn-outline-primary">Lihat Alternatif</a> --}}
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('admin') }}/assets/img/illustrations/man-with-laptop-light.png"
                                height="140" alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Jembatan -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0 border border-success text-success rounded p-2 bg-transparent">
                                <i class="menu-icon tf-icons bx bx-buildings"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Jembatan</span>
                        <h3 class="card-title mb-2">{{ $statistik['total_jembatan'] }}</h3>
                        <small class="text-success fw-semibold">Data Jembatan</small>
                    </div>
                </div>
            </div>

            <!-- Total Komponen -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0 border border-info text-info rounded p-2 bg-transparent">
                                <i class="menu-icon tf-icons bx bx-list-check"></i>
                            </div>
                        </div>
                        <span>Total Komponen</span>
                        <h3 class="card-title text-nowrap mb-1">{{ $statistik['total_komponen'] }}</h3>
                        <small class="text-success fw-semibold">Data Komponen</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User dan Penilaian -->
        <div class="col-12 col-md-12 col-lg-4 order-3 order-md-2">
            <!-- Total User -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0 border border-primary text-primary rounded p-2 bg-transparent">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                            </div>
                        </div>
                        <span class="d-block mb-1">Total User</span>
                        <h3 class="card-title text-nowrap mb-2">{{ $statistik['total_user'] }}</h3>
                        <small class="text-success fw-semibold">Pengguna Aktif</small>
                    </div>
                </div>
            </div>

            <!-- Total Penilaian -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0 border border-warning text-warning rounded p-2 bg-transparent">
                                <i class="menu-icon tf-icons bx bx-star"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Penilaian</span>
                        <h3 class="card-title mb-2">{{ $statistik['total_penilaian'] }}</h3>
                        <small class="text-success fw-semibold">Data Penilaian</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
