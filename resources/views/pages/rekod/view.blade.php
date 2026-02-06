@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Rekod</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('rekod') }}">Senarai Rekod</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Maklumat Rekod
                </li>
            </ol>
        </nav>
    </div>

    {{-- Button edit: controller dah kawal access --}}
    <div class="ms-auto">
        <a href="{{ route('rekod.edit', $rekod->id) }}">
            <button type="button" class="btn btn-primary mt-2 mt-lg-0">
                Kemaskini Maklumat
            </button>
        </a>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat Rekod</h6>
<hr />

<div class="row">
    {{-- Maklumat Pemilik --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3 text-uppercase">Maklumat Pengguna</h6>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="40%">Nama</th>
                        <td>{{ $rekod->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Pekerja</th>
                        <td>{{ $rekod->user->staff_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telefon</th>
                        <td>{{ $rekod->user->phone_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fakulti</th>
                        <td>{{ optional($rekod->user->ptj)->name ?? ($rekod->user->ptj_id ?? '-') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Maklumat Akademik --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3 text-uppercase">Maklumat Akademik</h6>
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="40%">Program</th>
                        <td>
                            {{ $rekod->program->kod ?? '-' }} -
                            {{ $rekod->program->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Kursus</th>
                        <td>
                            {{ $rekod->kursus->kod ?? '-' }} -
                            {{ $rekod->kursus->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Kumpulan</th>
                        <td>{{ $rekod->kumpulan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tarikh Hantar</th>
                        <td>{{ optional($rekod->created_at)->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Fail --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3 text-uppercase">Fail Dimuat Naik</h6>

                @if (!empty($rekod->file_path))
                    <a href="{{ asset('public/storage/' . $rekod->file_path) }}"
                       target="_blank"
                       class="btn btn-outline-primary">
                        <i class="bx bxs-file-pdf"></i> Buka Fail PDF
                    </a>
                @else
                    <span class="text-muted">Tiada fail</span>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End Page Wrapper -->
@endsection
