@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
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
                    <li class="breadcrumb-item active" aria-current="page">Senarai Rekod Dipadam</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">Senarai Rekod Dipadam</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>No. Pekerja</th>
                            <th>Fakulti</th>
                            <th>Program</th>
                            <th>Kursus</th>
                            <th>Kumpulan</th>
                            <th>Fail</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($trashList) > 0)
                            @foreach ($trashList as $rekod)
                                <tr>
                                    <td>
                                        {{ ($trashList->currentPage() - 1) * $trashList->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $rekod->user->name ?? '-' }}</td>
                                    <td>{{ $rekod->user->staff_id ?? '-' }}</td>
                                    <td>{{ optional($rekod->user->ptj)->name ?? ($rekod->user->ptj_id ?? '-') }}</td>
                                    <td>{{ $rekod->program->kod ?? '-' }} - {{ $rekod->program->name ?? '-' }}</td>
                                    <td>{{ $rekod->kursus->kod ?? '-' }} - {{ $rekod->kursus->name ?? '-' }}</td>
                                    <td>{{ $rekod->kumpulan ?? '-' }}</td>
                                    <td>
                                        @if (!empty($rekod->file_path))
                                            <a href="{{ asset('storage/' . $rekod->file_path) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Buka PDF">
                                                <i class="bx bxs-file-pdf"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rekod.restore', $rekod->id) }}" class="btn btn-success btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kembalikan">
                                            <i class="bx bx-undo"></i>
                                        </a>
                                        <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            data-bs-title="Padam">
                                            <span class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $rekod->id }}">
                                                <i class="bx bx-trash"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="9">Tiada rekod</td>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                    <form action="{{ route('rekod.trash') }}" method="GET" id="perPageForm">
                        <select name="perPage" id="perPage" class="form-select"
                            onchange="document.getElementById('perPageForm').submit()">
                            <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                            <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                        </select>
                    </form>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <div class="mx-1 mt-2">
                        {{ $trashList->firstItem() }} – {{ $trashList->lastItem() }} dari {{ $trashList->total() }} rekod
                    </div>
                    <div>
                        {{ $trashList->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @foreach ($trashList as $rekod)
        <div class="modal fade" id="deleteModal{{ $rekod->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Pengesahan Padam Rekod</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @isset($rekod)
                            Adakah anda pasti ingin memadam rekod ini secara kekal?
                        @else
                            Tiada rekod
                        @endisset
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        @isset($rekod)
                            <form class="d-inline" method="POST" action="{{ route('rekod.forceDelete', $rekod->id) }}">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger">Padam</button>
                            </form>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
