<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view team')->only(['index']);
        $this->middleware('permission:add team')->only(['create', 'store']);
        $this->middleware('permission:edit team')->only(['edit', 'update']);
        $this->middleware('permission:delete team')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::get();
        return view('team.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('team.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:teams,name']
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah dibuat',
        ], [
            'name' => 'Nama Team',
        ]);

        Team::create([
            'name' => $request->name,
        ]);

        return redirect()->route('team.index')->with('success', 'Team berhasil dibuat');
    }

    public function Show($id) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = Team::findOrFail($id);
        return view('team.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:teams,name,'.$id]
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Team',
        ]);

        Team::where('id', $id)->update([
            'name' => $request->name,
        ]);

        return redirect()->route('team.index')->with('success', 'Team berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Team::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Team Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
