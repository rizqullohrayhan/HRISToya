<?php

namespace App\Http\Controllers;

use App\Models\KodePerkiraan;
use Illuminate\Http\Request;

class KodePerkiraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kodePerkiraan = KodePerkiraan::all();
        $data = [
            'kodePerkiraan' => $kodePerkiraan,
        ];
        return view('data-voucher.kodeperkiraan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-voucher.kodeperkiraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'unique:kode_perkiraans,code'],
            'name' => ['required'],
        ], [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'code' => 'Kode Perkiraan',
            'name' => 'Nama Perkiraan',
        ]);

        KodePerkiraan::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('kodeperkiraan.index')->with('success', 'Kode Perkiraan berhasil ditambahkan');
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
        $kodePerkiraan = KodePerkiraan::findOrFail($id);
        return view('data-voucher.kodeperkiraan.edit', compact('kodePerkiraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => ['required', 'unique:kode_perkiraans,code,'.$id],
            'name' => ['required'],
        ], [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute telah digunakan',
        ], [
            'code' => 'Kode Perkiraan',
            'name' => 'Nama Perkiraan',
        ]);

        KodePerkiraan::where('id', $id)->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('kodeperkiraan.index')->with('success', 'Kode Perkiraan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            KodePerkiraan::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Kode Perkiraan Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
