<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use App\Models\Kantor;

class UserController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view user')->only(['index']);
        $this->middleware('permission:add user')->only(['create', 'store']);
        $this->middleware('permission:edit user')->only(['edit', 'update']);
        $this->middleware('permission:delete user')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles', 'team', 'kantor')->get();
        return view('role-permission.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get();
        $teams = Team::get();
        $kantors = Kantor::get();
        $data = [
            'roles' => $roles,
            'teams' => $teams,
            'kantors' => $kantors,
        ];
        return view('role-permission.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'jabatan' => ['required', 'string', 'max:255'],
            'role' => ['required', 'exists:roles,name'],
            'team' => ['required', 'exists:teams,id'],
            'kantor' => ['required', 'exists:kantors,id'],
            'picture' => ['required', 'image'],
            'password' => ['required', 'string', 'min:5', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/'],
        ], [
            'password.regex' => 'Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, angka.',
        ]);

        $image = $request->file('picture');
        $imageName = time().'.jpeg';
        $destinationPath = public_path('upload/profile_picture/'.$imageName);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);
        $image->toJpeg(80)->save($destinationPath);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'team_id' => $request->team,
            'kantor_id' => $request->kantor,
            'password' => Hash::make($request->password),
            'picture' => $imageName,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('user.index')->with('success', 'User berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('roles', 'team')->where('id', $id)->first();
        $roles = Role::get();
        $teams = Team::get();
        $kantors = Kantor::get();
        $data = [
            'user' => $user,
            'roles' => $roles,
            'teams' => $teams,
            'kantors' => $kantors,
        ];
        return view('role-permission.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'jabatan' => ['required', 'string', 'max:255'],
            'role' => ['required', 'exists:roles,name'],
            'team' => ['required', 'exists:teams,id'],
            'kantor' => ['required', 'exists:kantors,id'],
            'picture' => ['image'],
        ];

        if (isset($request->password)) {
            $rules['password'] = 'confirmed|min:5|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/';
        }

        $request->validate($rules, [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute tidak boleh lebih dari 255 karakter',
            'unique' => ':attribute telah digunakan',
            'email' => ':attribute harus berupa email',
            'password.regex' => 'Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, dan angka.',
        ], [
            'name' => 'Nama Lengkap',
            'username' => 'Username',
            'email' => 'Email',
            'role' => 'Role',
        ]);

        $dataUpdate = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'team_id' => $request->team,
            'kantor_id' => $request->kantor,
        ];

        if (isset($request->password)) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $user = User::findOrFail($id);

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $imageName = time().'.jpeg';
            $destinationPath = public_path('upload/profile_picture/'.$imageName);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->toJpeg(80)->save($destinationPath);
            if ($user->picture != null && file_exists(public_path('upload/profile_picture/'.$user->picture))) {
                unlink(public_path('upload/profile_picture/'.$user->picture));
            }
            // unlink(public_path('profile_picture/'.$user->picture));

            $dataUpdate['picture'] = $imageName;
        }

        $user->update($dataUpdate);
        $user->syncRoles($request->role);

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->picture != null && file_exists(public_path('upload/profile_picture/'.$user->picture))) {
            unlink(public_path('upload/profile_picture/'.$user->picture));
        }
        // unlink(public_path('upload/profile_picture/'.$user->picture));
        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'User Berhasil dihapus'], 200);
    }
}
