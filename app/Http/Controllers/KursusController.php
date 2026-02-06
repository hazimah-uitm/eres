<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Program;
use Illuminate\Http\Request;

class KursusController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $kursusList = Kursus::latest()->paginate($perPage);

        return view('pages.kursus.index', [
            'kursusList' => $kursusList,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $programList = Program::where('publish_status', 1)->get();

        return view('pages.kursus.create', [
            'save_route' => route('kursus.store'),
            'str_mode' => 'Tambah',
            'programList' => $programList,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'kod' => 'required|unique:kursuss',
            'name' => 'required|unique:kursuss',
            'publish_status' => 'required|in:1,0',
        ], [
            'name.required'     => 'Sila isi nama sub unit',
            'name.unique' => 'Nama sub unit telah wujud',
            'kod.required'     => 'Sila isi nama sub unit',
            'kod.unique' => 'Nama sub unit telah wujud',
            'program_id.required' => 'Sila isi bahagian/unit',
            'publish_status.required' => 'Sila isi status',
        ]);

        $kursus = new kursus();

        $kursus->fill($request->all());
        $kursus->save();

        return redirect()->route('kursus')->with('success', 'Maklumat berjaya disimpan');
    }

    public function show($id)
    {
        $kursus = Kursus::findOrFail($id);

        return view('pages.kursus.view', [
            'kursus' => $kursus,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $programList = Program::where('publish_status', 1)->get();

        return view('pages.kursus.edit', [
            'save_route' => route('kursus.update', $id),
            'str_mode' => 'Kemas Kini',
            'kursus' => Kursus::findOrFail($id),
            'programList' => $programList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'kod' => 'required|unique:kursuss,kod,' . $id,
            'name' => 'required|unique:kursuss,name,' . $id,
            'publish_status' => 'required|in:1,0',
        ], [
            'name.required'     => 'Sila isi nama sub unit',
            'name.unique' => 'Nama sub unit telah wujud',
            'kod.required'     => 'Sila isi nama sub unit',
            'kod.unique' => 'Nama sub unit telah wujud',
            'program_id.required' => 'Sila isi bahagian/unit',
            'publish_status.required' => 'Sila isi status',
        ]);

        $kursus = Kursus::findOrFail($id);

        $kursus->fill($request->all());
        $kursus->save();

        return redirect()->route('kursus')->with('success', 'Maklumat berjaya dikemaskini');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $kursusList = Kursus::where('name', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        } else {
            $kursusList = Kursus::latest()->paginate(10);
        }

        return view('pages.kursus.index', [
            'kursusList' => $kursusList,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $kursus = Kursus::findOrFail($id);

        $kursus->delete();

        return redirect()->route('kursus')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Kursus::onlyTrashed()->latest()->paginate(10);

        return view('pages.kursus.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Kursus::withTrashed()->where('id', $id)->restore();

        return redirect()->route('kursus')->with('success', 'Maklumat berjaya dikembalikan');
    }


    public function forceDelete($id)
    {
        $kursus = Kursus::withTrashed()->findOrFail($id);

        $kursus->forceDelete();

        return redirect()->route('kursus.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
