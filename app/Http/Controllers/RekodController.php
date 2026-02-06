<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Program;
use App\Models\Rekod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekodController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $user = User::find(auth()->id());

        $query = Rekod::with(['user', 'program', 'kursus'])->latest();

        if ($user->hasRole('Pengguna')) {
            $query->where('user_id', $user->id);
        }

        $rekodList = $query->paginate($perPage);

        return view('pages.rekod.index', [
            'rekodList' => $rekodList,
            'perPage'   => $perPage,
        ]);
    }

    public function create()
    {
        $user = User::find(auth()->id());

        // Program ikut PTJ user
        $programList = Program::where('ptj_id', $user->ptj_id)
            ->where('publish_status', 1)
            ->orderBy('kod')
            ->get();

        $programIds = $programList->pluck('id')->toArray();

        $kursusList = Kursus::whereIn('program_id', $programIds)
            ->where('publish_status', 1)
            ->orderBy('kod')
            ->get();

        return view('pages.rekod.create', [
            'save_route'  => route('rekod.store'),
            'str_mode'    => 'Tambah',
            'user'        => $user,
            'programList' => $programList,
            'kursusList'  => $kursusList,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|integer|exists:programs,id',
            'kursus_id'  => 'required|integer|exists:kursuses,id',
            'kumpulan'   => 'required|string|max:50',
            'file_pdf'   => 'required|mimes:pdf|max:5120',
        ], [
            'program_id.required' => 'Sila pilih program',
            'kursus_id.required'  => 'Sila pilih kursus',
            'kumpulan.required'   => 'Sila isi kumpulan',
            'file_pdf.required'   => 'Sila pilih fail PDF',
            'file_pdf.mimes'      => 'Fail mestilah dalam format PDF sahaja',
            'file_pdf.max'        => 'Saiz fail melebihi had (maksimum 5MB)',
        ]);

        $user = User::find(auth()->id());

        $program = Program::where('id', $request->program_id)
            ->where('ptj_id', $user->ptj_id)
            ->where('publish_status', 1)
            ->firstOrFail();

        $kursus = Kursus::where('id', $request->kursus_id)
            ->where('program_id', $program->id)
            ->where('publish_status', 1)
            ->firstOrFail();

        $path = $request->file('file_pdf')->store('rekod_pdf', 'public');

        $rekod = new Rekod();
        $rekod->user_id    = $user->id;
        $rekod->program_id = $program->id;
        $rekod->kursus_id  = $kursus->id;
        $rekod->kumpulan   = $request->kumpulan;
        $rekod->file_path  = $path;
        $rekod->save();

        return redirect()->route('rekod')->with('success', 'Fail berjaya dimuat naik');
    }

    public function show($id)
    {
        $user = User::find(auth()->id());
        $rekod = Rekod::with(['user', 'program', 'kursus'])->findOrFail($id);

        if ($user->hasRole('Pengguna') && $rekod->user_id != $user->id) {
            abort(403);
        }

        return view('pages.rekod.view', [
            'rekod' => $rekod,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $authUser = User::find(auth()->id());
        $rekod = Rekod::with('user')->findOrFail($id);

        if ($authUser->hasRole('Pengguna') && $rekod->user_id != $authUser->id) {
            abort(403);
        }

        $ownerPtjId = optional($rekod->user)->ptj_id;

        $programList = Program::where('ptj_id', $ownerPtjId)
            ->where('publish_status', 1)
            ->orderBy('kod')
            ->get();

        $programIds = $programList->pluck('id')->toArray();

        $kursusList = Kursus::whereIn('program_id', $programIds)
            ->where('publish_status', 1)
            ->orderBy('kod')
            ->get();

        return view('pages.rekod.edit', [
            'save_route'  => route('rekod.update', $id),
            'str_mode'    => 'Kemas Kini',
            'rekod'       => $rekod,
            'user'        => $rekod->user, 
            'programList' => $programList,
            'kursusList'  => $kursusList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $authUser = User::find(auth()->id());
        $rekod = Rekod::with('user')->findOrFail($id);

        if ($authUser->hasRole('Pengguna') && $rekod->user_id != $authUser->id) {
            abort(403);
        }

        $request->validate([
            'program_id' => 'required|integer|exists:programs,id',
            'kursus_id'  => 'required|integer|exists:kursuses,id',
            'kumpulan'   => 'required|string|max:50',
            'file_pdf'   => 'nullable|mimes:pdf|max:5120',
        ], [
            'program_id.required' => 'Sila pilih program',
            'kursus_id.required'  => 'Sila pilih kursus',
            'kumpulan.required'   => 'Sila isi kumpulan',
            'file_pdf.mimes'      => 'Fail mestilah dalam format PDF sahaja',
            'file_pdf.max'        => 'Saiz fail melebihi had (maksimum 5MB)',
        ]);

        $ownerPtjId = optional($rekod->user)->ptj_id;

        $program = Program::where('id', $request->program_id)
            ->where('ptj_id', $ownerPtjId)
            ->where('publish_status', 1)
            ->firstOrFail();

        $kursus = Kursus::where('id', $request->kursus_id)
            ->where('program_id', $program->id)
            ->where('publish_status', 1)
            ->firstOrFail();

        $rekod->program_id = $program->id;
        $rekod->kursus_id  = $kursus->id;
        $rekod->kumpulan   = $request->kumpulan;

        // kalau user upload pdf baru, replace
        if ($request->hasFile('file_pdf')) {
            $newPath = $request->file('file_pdf')->store('rekod_pdf', 'public');

            // delete lama
            if ($rekod->file_path && Storage::disk('public')->exists($rekod->file_path)) {
                Storage::disk('public')->delete($rekod->file_path);
            }

            $rekod->file_path = $newPath;
        }

        $rekod->save();

        return redirect()->route('rekod')->with('success', 'Maklumat berjaya dikemaskini');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $user = User::find(auth()->id());

        $query = Rekod::with(['user', 'program', 'kursus'])->latest();

        if ($user->hasRole('Pengguna')) {
            $query->where('user_id', $user->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kumpulan', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($qq) use ($search) {
                      $qq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('staff_id', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('program', function ($qq) use ($search) {
                      $qq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('kod', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('kursus', function ($qq) use ($search) {
                      $qq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('kod', 'LIKE', "%{$search}%");
                  });
            });
        }

        $rekodList = $query->paginate(10);

        return view('pages.rekod.index', [
            'rekodList' => $rekodList,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $authUser = User::find(auth()->id());
        $rekod = Rekod::findOrFail($id);

        if ($authUser->hasRole('Pengguna') && $rekod->user_id != $authUser->id) {
            abort(403);
        }

        $rekod->delete();

        return redirect()->route('rekod')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Rekod::onlyTrashed()->latest()->paginate(10);

        return view('pages.rekod.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Rekod::withTrashed()->where('id', $id)->restore();

        return redirect()->route('rekod')->with('success', 'Maklumat berjaya dikembalikan');
    }

    public function forceDelete($id)
    {
        $rekod = Rekod::withTrashed()->findOrFail($id);

        if ($rekod->file_path && Storage::disk('public')->exists($rekod->file_path)) {
            Storage::disk('public')->delete($rekod->file_path);
        }

        $rekod->forceDelete();

        return redirect()->route('rekod.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
