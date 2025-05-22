<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use Illuminate\Http\Request;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kantors = Kantor::all();
        return view('kantor.index', compact('kantors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kantor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'jarak_maks' => 'required|string|max:255',
        ]);

        Kantor::create($request->input());

        return redirect()->route('kantor.index')->with('success', 'Kantor created successfully.');
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
        $kantor = Kantor::findOrFail($id);
        return view('kantor.edit', compact('kantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'jarak_maks' => 'required|string|max:255',
        ]);

        $kantor = Kantor::findOrFail($id);
        $kantor->update($request->input());

        return redirect()->route('kantor.index')->with('success', 'Kantor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kantor = Kantor::findOrFail($id);
        $kantor->delete();

        return redirect()->route('kantor.index')->with('success', 'Kantor deleted successfully.');
    }
}
