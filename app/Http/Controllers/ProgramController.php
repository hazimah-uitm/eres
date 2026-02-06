<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Ptj;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $programList = Program::latest()->paginate($perPage);

        return view('pages.program.index', [
            'programList' => $programList,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $ptjList = Ptj::where('publish_status', 1)->get();

        return view('pages.program.create', [
            'save_route' => route('program.store'),
            'str_mode' => 'Tambah',
            'ptjList' => $ptjList,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ptj_id' => 'required|exists:ptjs,id',
            'kod' => 'required|unique:programs',
            'name' => 'required|unique:programs',
            'publish_status' => 'required|in:1,0',
        ], [
            'name.required'     => 'Sila isi nama sub unit',
            'name.unique' => 'Nama sub unit telah wujud',
            'kod.required'     => 'Sila isi nama sub unit',
            'kod.unique' => 'Nama sub unit telah wujud',
            'ptj_id.required' => 'Sila isi bahagian/unit',
            'publish_status.required' => 'Sila isi status',
        ]);

        $program = new program();

        $program->fill($request->all());
        $program->save();

        return redirect()->route('program')->with('success', 'Maklumat berjaya disimpan');
    }

    public function show($id)
    {
        $program = Program::findOrFail($id);

        return view('pages.program.view', [
            'program' => $program,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $ptjList = Ptj::where('publish_status', 1)->get();

        return view('pages.program.edit', [
            'save_route' => route('program.update', $id),
            'str_mode' => 'Kemas Kini',
            'program' => Program::findOrFail($id),
            'ptjList' => $ptjList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ptj_id' => 'required|exists:ptjs,id',
            'kod' => 'required|unique:programs,kod,' . $id,
            'name' => 'required|unique:programs,name,' . $id,
            'publish_status' => 'required|in:1,0',
        ], [
            'name.required'     => 'Sila isi nama sub unit',
            'name.unique' => 'Nama sub unit telah wujud',
            'kod.required'     => 'Sila isi nama sub unit',
            'kod.unique' => 'Nama sub unit telah wujud',
            'ptj_id.required' => 'Sila isi bahagian/unit',
            'publish_status.required' => 'Sila isi status',
        ]);

        $program = Program::findOrFail($id);

        $program->fill($request->all());
        $program->save();

        return redirect()->route('program')->with('success', 'Maklumat berjaya dikemaskini');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $programList = Program::where('name', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        } else {
            $programList = Program::latest()->paginate(10);
        }

        return view('pages.program.index', [
            'programList' => $programList,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $program->delete();

        return redirect()->route('program')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Program::onlyTrashed()->latest()->paginate(10);

        return view('pages.program.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Program::withTrashed()->where('id', $id)->restore();

        return redirect()->route('program')->with('success', 'Maklumat berjaya dikembalikan');
    }


    public function forceDelete($id)
    {
        $program = Program::withTrashed()->findOrFail($id);

        $program->forceDelete();

        return redirect()->route('program.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
