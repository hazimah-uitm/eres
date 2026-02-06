@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Kursus</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('kursus') }}">Senarai Kursus</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Kursus</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">{{ $str_mode }} Kursus</h6>
<hr />

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ $save_route }}">
            {{ csrf_field() }}

            <div class="mb-3">
                <label for="program_id" class="form-label">Program</label>
                <select class="form-select {{ $errors->has('program_id') ? 'is-invalid' : '' }}" id="program_id"
                    name="program_id">
                    <option value="" disabled selected>Pilih Program</option>
                    @foreach ($programList as $program)
                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                        {{ $program->name }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('program'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('program') as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="mb-3">
                <label for="kod" class="form-label">Kod Kursus</label>
                <input type="text" class="form-control {{ $errors->has('kod') ? 'is-invalid' : '' }}" id="kod"
                    name="kod" value="{{ old('kod') }}">
                @if ($errors->has('kod'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('kod') as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kursus</label>
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                    name="name" value="{{ old('name') }}">
                @if ($errors->has('name'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('name') as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="publish_status" class="form-label">Status</label>
                <div class="form-check">
                    <input type="radio" id="aktif" name="publish_status" value="1"
                        {{ old('publish_status') == '1' || ($kursus->publish_status ?? false) ? 'checked' : '' }}
                        required>
                    <label class="form-check-label" for="aktif">Aktif</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="tidak_aktif" name="publish_status" value="0"
                        {{ old('publish_status') == '0' || !($kursus->publish_status ?? true) ? 'checked' : '' }}
                        required>
                    <label class="form-check-label" for="tidak_aktif">Tidak Aktif</label>
                </div>
                @if ($errors->has('publish_status'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('publish_status') as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
        </form>
    </div>
</div>
<!-- End Page Wrapper -->
@endsection