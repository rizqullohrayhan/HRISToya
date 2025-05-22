<?php

namespace App\Http\Controllers;

use App\Models\MasterDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MasterDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('ADM')) {
            $master_dokumen = MasterDokumen::with('user')->get();
        } else {
            $master_dokumen = MasterDokumen::with('user')->where('user_id', Auth::user()->id)->get();
        }
        return view('master_dokumen.index', [
            'dokumens' => $master_dokumen,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master_dokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $request->name;
        $file->storeAs('masterDokumens', $filename, 'local');

        MasterDokumen::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'tipe' => $file->getClientOriginalExtension(),
            'keterangan' => $request->keterangan,
            'ukuran' => $file->getSize(),
            'file' => $filename,
        ]);

        return redirect()->route('masterdokumen.index')->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokumen = MasterDokumen::findOrFail($id);
        return view('master_dokumen.show', [
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dokumen = MasterDokumen::findOrFail($id);
        return view('master_dokumen.edit', [
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file',
        ]);

        $dokumen = MasterDokumen::findOrFail($id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $request->name;
            $file->storeAs('masterDokumens', $filename, 'local');
            if(Storage::disk('local')->exists('masterDokumens/' . $dokumen->file)) {
                Storage::disk('local')->delete('masterDokumens/' . $dokumen->file);
            }
            $dokumen->file = $filename;
            $dokumen->ukuran = $file->getSize();
            $dokumen->tipe = $file->getClientOriginalExtension();
        }

        $dokumen->name = $request->name;
        $dokumen->keterangan = $request->keterangan;
        $dokumen->save();

        return redirect()->route('masterdokumen.index')->with('success', 'File updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokumen = MasterDokumen::findOrFail($id);
        if (Storage::disk('local')->exists('masterDokumens/' . $dokumen->file)) {
            Storage::disk('local')->delete('masterDokumens/' . $dokumen->file);
        }
        $dokumen->delete();

        return redirect()->route('masterdokumen.index')->with('success', 'File deleted successfully.');
    }

    public function download($id)
    {
        $dokumen = MasterDokumen::findOrFail($id);
        $authUser = Auth::user();

        if (!$authUser->hasRole('ADM') && $dokumen->user_id !== $authUser->id) {
            return redirect()->route('masterdokumen.index')->with('error', 'You do not have permission to download this file.');
        }

        $path = storage_path("app/private/masterDokumens/{$dokumen->file}");

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }
}
