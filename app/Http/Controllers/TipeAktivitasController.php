<?php

namespace App\Http\Controllers;

use App\Models\TipeAktivitas;
use Illuminate\Http\Request;

class TipeAktivitasController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view tipe aktivitas')->only(['index', 'show']);
        $this->middleware('permission:add tipe aktivitas')->only(['create', 'store']);
        $this->middleware('permission:edit tipe aktivitas')->only(['edit', 'update']);
        $this->middleware('permission:delete tipe aktivitas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipe = TipeAktivitas::all();
        $data = [
            'tipes' => $tipe,
        ];
        return view('data-aktivitas.tipe.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-aktivitas.tipe.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:tipe_aktivitas,name'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Tipe Aktivitas'
        ]);

        TipeAktivitas::create([
            'name' => $request->name
        ]);

        return redirect()->route('tipeaktivitas.index')->with('success', 'Tipe Aktivitas berhasil ditambahkan');
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
        $tipe = TipeAktivitas::findOrFail($id);
        return view('data-aktivitas.tipe.edit', compact('tipe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:tipe_aktivitas,name,'.$id],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Tipe Aktivitas'
        ]);

        TipeAktivitas::where('id', $id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('tipeaktivitas.index')->with('success', 'Tipe Aktivitas berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            TipeAktivitas::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Tipe Aktivitas Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
