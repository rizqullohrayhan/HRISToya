<?php

namespace App\Http\Controllers;

use App\Models\TipeVoucher;
use Illuminate\Http\Request;

class TipeVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipe = TipeVoucher::all();
        $data = [
            'tipes' => $tipe,
        ];
        return view('data-voucher.tipe.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data-voucher.tipe.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:tipe_vouchers,name'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Tipe Voucher'
        ]);

        TipeVoucher::create([
            'name' => $request->name
        ]);

        return redirect()->route('tipevoucher.index')->with('success', 'Tipe Voucher berhasil ditambahkan');
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
        $tipe = TipeVoucher::findOrFail($id);
        return view('data-voucher.tipe.edit', compact('tipe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:tipe_vouchers,name,'.$id],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'unique' => ':attribute telah terdaftar pada database',
        ], [
            'name' => 'Nama Tipe Voucher'
        ]);

        TipeVoucher::where('id', $id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('tipevoucher.index')->with('success', 'Tipe Voucher berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            TipeVoucher::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Tipe Voucher Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
