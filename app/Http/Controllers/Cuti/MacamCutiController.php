<?php

namespace App\Http\Controllers\Cuti;

use App\Http\Controllers\Controller;
use App\Models\MacamCuti;
use Illuminate\Http\Request;

class MacamCutiController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view macam ijin')->only(['index', 'show']);
        $this->middleware('permission:add macam ijin')->only(['create', 'store']);
        $this->middleware('permission:edit macam ijin')->only(['edit', 'update']);
        $this->middleware('permission:delete macam ijin')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $macams = MacamCuti::all();
        return view('data-cuti.macam.index', compact('macams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-cuti.macam.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:macam_cutis,name'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Macam Cuti'
        ]);

        MacamCuti::create([
            'name' => $request->name
        ]);

        return redirect()->route('macamcuti.index')->with('success', 'Macam Cuti berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $macam = MacamCuti::findOrFail($id);
        return view('data-cuti.macam.edit', compact('macam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:macam_cutis,name,'.$id],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Macam Cuti'
        ]);

        MacamCuti::where('id', $id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('macamcuti.index')->with('success', 'Macam Cuti berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            MacamCuti::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Macam Cuti Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
