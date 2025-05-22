<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'jabatan' => ['required', 'string', 'max:255'],
            'picture' => ['image'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute tidak boleh lebih dari 255 karakter',
            'unique' => ':attribute telah digunakan',
            'email' => ':attribute harus berupa email',
        ], [
            'name' => 'Nama Lengkap',
            'username' => 'Username',
            'email' => 'Email',
            'picture' => 'Foto profile',
        ]);

        $dataUpdate = [
            'name' => $request->name,
            'username' => $request->username,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
        ];

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $imageName = time().'.jpeg';
            $destinationPath = public_path('upload/profile_picture/'.$imageName);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->toJpeg(80)->save($destinationPath);
            if (\File::exists(public_path('upload/profile_picture/'.$user->picture))) {
                unlink(public_path('upload/profile_picture/'.$user->picture));
            }

            $dataUpdate['picture'] = $imageName;
        }

        $user->update($dataUpdate);

        return redirect()->route('profile.edit')->with('success', 'Profile berhasil diupdate');
    }

    public function change_password()
    {
        return view('profile.reset_password');
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'old_pass' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Password lama tidak sesuai.');
                    }
                }
            ],
            'password' => ['required','min:5','confirmed','different:old_pass','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/'],
            'password_confirmation' => 'required'
        ], [
            'old_pass.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.different' => 'Password baru tidak boleh sama dengan password lama.',
            'password.regex' => 'Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, dan angka.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
