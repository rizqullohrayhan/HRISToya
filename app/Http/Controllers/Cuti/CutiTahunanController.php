<?php

namespace App\Http\Controllers\Cuti;

use App\Http\Controllers\Controller;
use App\Models\CutiTahunan;
use App\Models\User;
use Illuminate\Http\Request;

class CutiTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuti = CutiTahunan::with('user')->get();
        $data =[
            'cutis' => $cuti
        ];
        return view('data-cuti.tahunan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::all();
        $data = [
            'users' => $user
        ];
        return view('data-cuti.tahunan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'tahun' => [
                'required',
                'integer',
                Rule::unique('cuti_tahunans')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                }),
            ],
            'total_jatah' => ['required', 'integer'],
            'tambahan' => ['nullable', 'integer'],
        ]);

        CutiTahunan::create([
            'user_id' => $request->user_id,
            'tahun' => $request->tahun,
            'total_jatah' => $request->total_jatah,
            'tambahan' => $request->tambahan,
        ]);

        return redirect()->route('cutitahunan.index')->with('success', 'Cuti Tahunan berhasil ditambahkan');
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
        $user = User::all();
        $cuti = CutiTahunan::findOrFail($id);
        $data = [
            'users' => $user,
            'cuti' => $cuti,
        ];
        return view('data-cuti.tahunan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
