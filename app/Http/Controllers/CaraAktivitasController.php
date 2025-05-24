<?php

namespace App\Http\Controllers;

use App\Models\CaraAktivitas;
use Illuminate\Http\Request;

class CaraAktivitasController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view cara aktivitas')->only(['index', 'show']);
        $this->middleware('permission:add cara aktivitas')->only(['create', 'store']);
        $this->middleware('permission:edit cara aktivitas')->only(['edit', 'update']);
        $this->middleware('permission:delete cara aktivitas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cara = CaraAktivitas::all();
        $data = [
            'caras' => $cara,
        ];
        return view('data-aktivitas.cara.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-aktivitas.cara.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:cara_aktivitas,name'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Cara Aktivitas'
        ]);

        CaraAktivitas::create([
            'name' => $request->name
        ]);

        return redirect()->route('caraaktivitas.index')->with('success', 'Cara Aktivitas berhasil ditambahkan');
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
        $cara = CaraAktivitas::findOrFail($id);
        return view('data-aktivitas.cara.edit', compact('cara'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:cara_aktivitas,name,'.$id],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Cara Aktivitas'
        ]);

        CaraAktivitas::where('id', $id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('caraaktivitas.index')->with('success', 'Cara Aktivitas berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            CaraAktivitas::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Cara Aktivitas Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
