<?php

namespace App\Http\Controllers;

use App\Models\MataUang;
use Illuminate\Http\Request;

class MataUangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mataUang = MataUang::all();
        $data = [
            'mataUang' => $mataUang,
        ];
        return view('data-voucher.matauang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-voucher.matauang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'unique:mata_uangs,code'],
            'name' => ['required'],
        ], [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'code' => 'Singkatan Mata Uang',
            'name' => 'Nama Mata Uang',
        ]);

        MataUang::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('matauang.index')->with('success', 'Mata Uang berhasil ditambahkan');
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
        $mataUang = MataUang::findOrFail($id);
        return view('data-voucher.matauang.edit', compact('mataUang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => ['required', 'unique:mata_uangs,code,'.$id],
            'name' => ['required'],
        ], [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute telah digunakan',
        ], [
            'code' => 'Singkatan Mata Uang',
            'name' => 'Nama Mata Uang',
        ]);

        MataUang::where('id', $id)->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('matauang.index')->with('success', 'Mata Uang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            MataUang::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Mata Uang Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
