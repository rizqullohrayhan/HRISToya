<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'jabatan' => ['required', 'string', 'max:255'],
            'picture' => ['required', 'image'],
            'password' => ['required', 'string', 'min:5', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/'],
        ], [
            'password.regex' => 'Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, angka.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $imageName = time().'.jpeg';

        $team = Team::where('name', 'PT Toya Indo Manunggal')->first();
        $kantor = Team::first();

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'jabatan' => $data['jabatan'],
            'password' => Hash::make($data['password']),
            'picture' => $imageName,
            'team_id' => $team->id,
            'kantor_id' => $kantor->id,
        ]);

        $image = request()->file('picture');
        $destinationPath = public_path('upload/profile_picture/'.$imageName);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);
        $image->toJpeg(50)->save($destinationPath);

        $user->assignRole('OPR');

        return $user;
    }
}
