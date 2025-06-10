@extends('template-admin.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 mb-4 order-0">
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

                                <a href="{{ route('alternatif') }}" class="btn btn-sm btn-outline-primary">Lihat
                                    Alternatif</a>
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
            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('admin') }}/assets/img/icons/unicons/chart-success.png"
                                            alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Total Jembatan</span>
                                <h3 class="card-title mb-2">{{ $statistik['total_jembatan'] }}</h3>
                                <small class="text-success fw-semibold">Data Jembatan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('admin') }}/assets/img/icons/unicons/wallet-info.png"
                                            alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span>Total Komponen</span>
                                <h3 class="card-title text-nowrap mb-1">{{ $statistik['total_komponen'] }}</h3>
                                <small class="text-success fw-semibold">Data Komponen</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Revenue -->
            <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    {{-- <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <h5 class="card-header m-0 me-2 pb-3">Statistik Penilaian</h5>
                            <div id="totalRevenueChart" class="px-2"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                            id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Tahun {{ date('Y') }}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                            <a class="dropdown-item" href="javascript:void(0);">{{ date('Y') - 1 }}</a>
                                            <a class="dropdown-item" href="javascript:void(0);">{{ date('Y') - 2 }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="growthChart"></div>
                            <div class="text-center fw-semibold pt-3 mb-2">Total Penilaian:
                                {{ $statistik['total_penilaian'] }}</div>

                            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-primary p-2"><i
                                                class="bx bx-user text-primary"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>Total User</small>
                                        <h6 class="mb-0">{{ $statistik['total_user'] }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-info p-2"><i
                                                class="bx bx-check-circle text-info"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>Penilaian</small>
                                        <h6 class="mb-0">{{ $statistik['total_penilaian'] }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <!--/ Total Revenue -->
            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('admin') }}/assets/img/icons/unicons/paypal.png"
                                            alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span class="d-block mb-1">Total User</span>
                                <h3 class="card-title text-nowrap mb-2">{{ $statistik['total_user'] }}</h3>
                                <small class="text-success fw-semibold">Pengguna Aktif</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('admin') }}/assets/img/icons/unicons/cc-primary.png"
                                            alt="Credit Card" class="rounded" />
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
    </div>
@endsection

@push('scripts')
    <script>
        // Inisialisasi grafik
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Total Revenue
            const totalRevenueChart = new ApexCharts(document.querySelector("#totalRevenueChart"), {
                series: [{
                    name: 'Penilaian',
                    data: [{{ $statistik['total_penilaian'] }}]
                }],
                chart: {
                    height: 300,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val;
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                xaxis: {
                    categories: ["Total Penilaian"],
                    position: 'bottom',
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    crosshairs: {
                        fill: {
                            type: 'gradient',
                            gradient: {
                                colorFrom: '#D8E3F0',
                                colorTo: '#BED1E6',
                                stops: [0, 100],
                                opacityFrom: 0.4,
                                opacityTo: 0.5
                            }
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                yaxis: {
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                title: {
                    text: 'Statistik Penilaian',
                    floating: true,
                    offsetY: 330,
                    align: 'center',
                    style: {
                        color: '#444'
                    }
                }
            });
            totalRevenueChart.render();

            // Grafik Profile Report
            const profileReportChart = new ApexCharts(document.querySelector("#profileReportChart"), {
                series: [{
                    name: 'Penilaian',
                    data: [{{ $statistik['total_penilaian'] }}]
                }],
                chart: {
                    height: 80,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    dropShadow: {
                        enabled: true,
                        top: 10,
                        left: 5,
                        blur: 3,
                        color: config.colors.warning,
                        opacity: 0.15
                    },
                    sparkline: {
                        enabled: true
                    }
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
                colors: [config.colors.warning],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 5
                },
                xaxis: {
                    show: false,
                    lines: {
                        show: false
                    },
                    labels: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    }
                },
                yaxis: {
                    show: false
                },
                markers: {
                    size: 0
                },
                tooltip: {
                    x: {
                        show: false
                    },
                    y: {
                        title: {
                            formatter: function() {
                                return '';
                            }
                        }
                    }
                }
            });
            profileReportChart.render();
        });
    </script>
@endpush
