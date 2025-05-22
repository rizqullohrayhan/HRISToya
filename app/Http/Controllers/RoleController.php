<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view role')->only(['index']);
        $this->middleware('permission:add role')->only(['create', 'store']);
        $this->middleware('permission:edit role')->only(['edit', 'update', 'edit permission', 'update permission']);
        $this->middleware('permission:delete role')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::orderBy('name')->get();
        $data = [
            'roles' => $role
        ];

        return view('role-permission.role.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name']
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah dibuat',
        ], [
            'name' => 'Nama Role',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil dibuat');
    }

    public function Show($id) {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('role-permission.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,'.$id]
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa huruf',
            'unique' => ':attribute sudah ada',
        ], [
            'name' => 'Nama Role',
        ]);

        Role::where('id', $id)->update([
            'name' => $request->name,
        ]);

        return redirect()->route('role.index')->with('success', 'Role berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Role::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Role Berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 505);
        }
    }

    public function edit_permission(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')
                            ->where('role_has_permissions.role_id', $id)
                            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                            ->all();

        $data = [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ];
        return view('role-permission.role.edit_permission', $data);
    }

    public function update_permission(Request $request, string $id)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permission);

        return redirect()->route('role.index')->with('success', 'Role permission berhasil diubah');
    }
}
