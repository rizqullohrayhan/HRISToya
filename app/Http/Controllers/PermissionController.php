<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view permission')->only(['index']);
        $this->middleware('permission:add permission')->only(['create', 'store']);
        $this->middleware('permission:edit permission')->only(['edit', 'update']);
        $this->middleware('permission:delete permission')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = Permission::orderBy('name')->get();
        $data = [
            'permissions' => $permission
        ];

        return view('role-permission.permission.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name']
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah dibuat',
        ], [
            'name' => 'Nama Permission',
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect()->route('permission.index')->with('success', 'Permission berhasil dibuat');
    }

    public function Show($id) {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('role-permission.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name,'.$id]
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Permission',
        ]);

        Permission::where('id', $id)->update([
            'name' => $request->name,
        ]);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('permission.index')->with('success', 'Permission berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Permission::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Permission Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }
}
