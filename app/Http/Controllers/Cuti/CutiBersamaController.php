<?php

namespace App\Http\Controllers\Cuti;

use App\Http\Controllers\Controller;
use App\Models\CutiBersama;
use Illuminate\Http\Request;

class CutiBersamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('data-cuti.bersama.index');
    }

    public function data(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $events = CutiBersama::whereBetween('tanggal', [$start, $end])->get();
        $eventsMap = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->keterangan,
                'start' => $event->tanggal,
            ];
        });

        return response()->json($eventsMap);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CutiBersama $cutibersama)
    {
        $data = [
            'action' => route('cutibersama.store'),
            'data' => $cutibersama
        ];
        return view('data-cuti.bersama.form-create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
        ]);

        CutiBersama::create([
            'tahun' => date('Y', strtotime($request->tanggal)),
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cuti Bersama berhasil ditambahkan',
        ]);
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
    public function edit(CutiBersama $cutibersama)
    {
        $data = [
            'action' => route('cutibersama.update', $cutibersama),
            'data' => $cutibersama
        ];
        return view('data-cuti.bersama.form-create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
        ]);

        CutiBersama::where('id', $id)->update([
            'tahun' => date('Y', strtotime($request->tanggal)),
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cuti Bersama berhasil diupdate',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CutiBersama $cutibersama)
    {
        $cutibersama->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Cuti Bersama berhasil dihapus',
        ]);
    }
}
