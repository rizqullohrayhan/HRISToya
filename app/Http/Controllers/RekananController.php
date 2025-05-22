<?php

namespace App\Http\Controllers;

use App\Models\Rekanan;
use Illuminate\Http\Request;

class RekananController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view rekan')->only(['index']);
        $this->middleware('permission:add rekan')->only(['create', 'store']);
        $this->middleware('permission:edit rekan')->only(['edit', 'update']);
        $this->middleware('permission:delete rekan')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekanans = Rekanan::get();
        return view('rekanan.index', compact('rekanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rekanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'unique:rekanans,code'],
            'name' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah dibuat',
        ], [
            'name' => 'Nama Rekanan',
            'code' => 'Kode',
        ]);

        Rekanan::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('rekan.index')->with('success', 'Rekanan berhasil dibuat');
    }

    public function Show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rekanan = Rekanan::findOrFail($id);
        return view('rekanan.edit', compact('rekanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => ['required', 'string', 'unique:rekanans,code,' . $id],
            'name' => ['nullable', 'string']
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Rekanan',
            'code' => 'Kode',
        ]);

        Rekanan::where('id', $id)->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('rekan.index')->with('success', 'Rekanan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Rekanan::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Rekanan Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
