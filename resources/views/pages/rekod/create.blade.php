@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Muat Naik Rekod</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rekod') }}">Senarai Rekod</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Rekod</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">{{ $str_mode }} Rekod</h6>
    <hr />

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ $save_route }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                {{-- Maklumat Pengguna (tarik dari user table) --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Pekerja</label>
                        <input type="text" class="form-control" value="{{ $user->staff_id }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telefon</label>
                        <input type="text" class="form-control" value="{{ $user->phone_no }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fakulti</label>
                        <input type="text" class="form-control" value="{{ optional($user->ptj)->name ?? $user->ptj_id }}"
                            readonly>
                    </div>
                </div>

                {{-- Program --}}
                <div class="mb-3">
                    <label for="program_id" class="form-label">Kod Program (Nama Program)</label>
                    <select class="tom-select {{ $errors->has('program_id') ? 'is-invalid' : '' }}" id="program_id"
                        name="program_id" required>
                        <option value="" disabled selected>Pilih Program</option>
                        @foreach ($programList as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->kod }} - {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('program_id'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('program_id') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Kursus (akan filter ikut program - tanpa AJAX) --}}
                <div class="mb-3">
                    <label for="kursus_id" class="form-label">Kod Kursus (Nama Kursus)</label>
                    <select class="tom-select {{ $errors->has('kursus_id') ? 'is-invalid' : '' }}" id="kursus_id"
                        name="kursus_id" required>
                        <option value="" disabled selected>Pilih Kursus</option>
                        @foreach ($kursusList as $kursus)
                            <option value="{{ $kursus->id }}" data-program-id="{{ $kursus->program_id }}"
                                {{ old('kursus_id') == $kursus->id ? 'selected' : '' }}>
                                {{ $kursus->kod }} - {{ $kursus->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('kursus_id'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('kursus_id') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Kumpulan --}}
                <div class="mb-3">
                    <label for="kumpulan" class="form-label">Kumpulan</label>
                    <input type="text" class="form-control {{ $errors->has('kumpulan') ? 'is-invalid' : '' }}"
                        id="kumpulan" name="kumpulan" value="{{ old('kumpulan') }}" required>
                    @if ($errors->has('kumpulan'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('kumpulan') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Fail PDF --}}
                <div class="mb-3">
                    <label for="file_pdf" class="form-label">Fail PDF</label>
                    <input type="file" class="form-control {{ $errors->has('file_pdf') ? 'is-invalid' : '' }}"
                        id="file_pdf" name="file_pdf" accept="application/pdf" required>
                    @if ($errors->has('file_pdf'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('file_pdf') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <small class="text-muted">Format: PDF sahaja. Maksimum 5MB.</small>
                </div>

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tom Select init
            const programTS = new TomSelect('#program_id', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Cari & pilih program...'
            });

            const kursusSelect = document.getElementById('kursus_id');
            const allKursusOptions = Array.from(kursusSelect.options).map(opt => ({
                value: opt.value,
                text: opt.text,
                programId: opt.getAttribute('data-program-id'),
                selected: opt.selected,
                disabled: opt.disabled
            }));

            const kursusTS = new TomSelect('#kursus_id', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Cari & pilih kursus...'
            });

            function resetKursus(keepSelected = false) {
                if (!keepSelected) kursusTS.clear(true);
                kursusTS.clearOptions();

                // placeholder
                kursusTS.addOption({
                    value: '',
                    text: 'Pilih Kursus'
                });
                kursusTS.refreshOptions(false);
            }

            function filterKursusByProgram(programId) {
                kursusTS.clear(true);
                kursusTS.clearOptions();

                kursusTS.addOption({
                    value: '',
                    text: 'Pilih Kursus'
                });

                allKursusOptions.forEach(o => {
                    if (!o.value) return;
                    if (String(o.programId) === String(programId)) {
                        kursusTS.addOption({
                            value: o.value,
                            text: o.text
                        });
                    }
                });

                kursusTS.refreshOptions(false);
            }

            // Initial filtering when old('program_id') exists
            const initialProgramId = programTS.getValue();
            if (initialProgramId) {
                filterKursusByProgram(initialProgramId);

                // restore old selected kursus if any
                const oldKursusId = "{{ old('kursus_id') }}";
                if (oldKursusId) {
                    kursusTS.setValue(oldKursusId, true);
                }
            } else {
                resetKursus(true);
            }

            programTS.on('change', function(value) {
                if (!value) {
                    resetKursus();
                    return;
                }
                filterKursusByProgram(value);
            });
        });
    </script>

@endsection
