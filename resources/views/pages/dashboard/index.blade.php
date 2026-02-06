@extends('layouts.master')

@section('content')

    <h6 class="mb-0 text-uppercase">
        Selamat datang, {{ $user->name }}
    </h6>
    <hr />

    {{-- QUICK STATS --}}
    @if ($isAdmin)
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Jumlah Rekod</p>
                                <h4 class="my-1">{{ $totalRekod }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto">
                                <i class="bx bx-data"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Rekod Bulan Ini</p>
                                <h4 class="my-1">{{ $thisMonth }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto">
                                <i class="bx bx-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Jumlah Program</p>
                                <h4 class="my-1">{{ $totalPrograms }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto">
                                <i class="bx bx-book-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Jumlah Kursus</p>
                                <h4 class="my-1">{{ $totalKursuses }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                <i class="bx bx-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        {{-- PENGGUNA: paksa 3 card sebaris bila desktop --}}
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Rekod Saya</p>
                                <h4 class="my-1">{{ $myTotal }}</h4>
                                <p class="mb-0 font-13 text-muted">Jumlah keseluruhan</p>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto">
                                <i class="bx bx-folder-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Rekod Bulan Ini</p>
                                <h4 class="my-1">{{ $myThisMonth }}</h4>
                                <p class="mb-0 font-13 text-muted">{{ date('m/Y') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto">
                                <i class="bx bx-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Muat Naik Fail</p>
                                <a href="{{ route('rekod.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bx bxs-plus-square"></i> Tambah Rekod
                                </a>
                            </div>
                            <div class="widgets-icons bg-light-info text-info ms-auto">
                                <i class="bx bx-upload"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3">
        {{-- Admin: Top fakulti --}}
        @if ($isAdmin)
            <div class="col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 text-uppercase">Rekod Mengikut Fakulti</h6>
                        </div>

                        @if (!empty($byPtj) && count($byPtj) > 0)
                            <div style="height: 300px;">
                                <canvas id="ptjBarChart"></canvas>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-bar-chart-alt-2" style="font-size:48px; opacity:.35;"></i>
                                <h6 class="mt-2 mb-1">Tiada data</h6>
                                <p class="text-muted mb-0">Graf akan dipaparkan apabila rekod wujud.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 text-uppercase">Rekod Terkini (Semua)</h6>
                            <div class="ms-auto">
                                <a href="{{ route('rekod') }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Program</th>
                                        <th>Tarikh</th>
                                        <th class="text-end">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestRekod as $r)
                                        <tr>
                                            <td>{{ $r->user->name ?? '-' }}</td>
                                            <td>{{ $r->program->kod ?? '-' }}</td>
                                            <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('rekod.show', $r->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Tiada rekod</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        @else
            {{-- Pengguna: rekod saya --}}
            <div class="col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 text-uppercase">Rekod Saya (Terkini)</h6>
                            <div class="ms-auto">
                                <a href="{{ route('rekod') }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Program</th>
                                        <th>Kursus</th>
                                        <th>Kumpulan</th>
                                        <th>Tarikh</th>
                                        <th class="text-end">Fail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myLatest as $r)
                                        <tr>
                                            <td>{{ $r->program->kod ?? '-' }} - {{ $r->program->name ?? '-' }}</td>
                                            <td>{{ $r->kursus->kod ?? '-' }} - {{ $r->kursus->name ?? '-' }}</td>
                                            <td>{{ $r->kumpulan ?? '-' }}</td>
                                            <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
                                            <td class="text-end">
                                                @if (!empty($r->file_path))
                                                    <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bx bxs-file-pdf"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">Tiada rekod</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var byPtj = {!! json_encode($byPtj ?? []) !!};

            if (byPtj.length > 0) {
                var labels = byPtj.map(function(x) {
                    return x.name;
                });
                var values = byPtj.map(function(x) {
                    return x.total;
                });

                var ctx = document.getElementById('ptjBarChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Rekod',
                            data: values,
                            backgroundColor: [
                                '#4e73df',
                                '#1cc88a',
                                '#36b9cc',
                                '#f6c23e',
                                '#e74a3b',
                                '#858796',
                                '#20c997',
                                '#0dcaf0',
                                '#fd7e14',
                                '#6f42c1'
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: false,
                                text: ['JUMLAH REKOD MENGIKUT FAKULTI'],
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#000',
                                padding: {
                                    bottom: 10
                                }
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

        });
    </script>

@endsection
