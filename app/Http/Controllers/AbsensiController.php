<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JenisAbsen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use DataTables;

class AbsensiController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view absen|view other absen')->only(['index', 'get_data', 'show']);
        $this->middleware('permission:add absen')->only(['create', 'store']);
        $this->middleware('permission:edit absen')->only(['edit', 'update']);
        $this->middleware('permission:delete absen')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = \Carbon\Carbon::today();
        if ($today->day < 28) {
            $start = $today->copy()->subMonthNoOverflow()->day(28); // 28 bulan lalu
            $end = $today->copy()->day(27);                          // 27 bulan ini
        } else {
            $start = $today->copy()->day(28);                        // 28 bulan ini
            $end = $today->copy()->addMonthNoOverflow()->day(27);    // 27 bulan depan
        }

        $data = [
            'start' => $start->format('d/m/Y'),
            'end' => $end->format('d/m/Y'),
        ];
        if (Auth::user()->hasRole('ADM')) {
            $data['users'] = User::get();
        } elseif (Auth::user()->hasPermissionTo('view other absen')) {
            $data['users'] = User::where('team_id', Auth::user()->team_id)->get();
        }
        return view('aktivitas.absen.index', $data);
    }

    // public function get_data(Request $request)
    // {
    //     try {
    //         $start = Carbon::createFromFormat('d/m/Y', $request->startdate)->format('Y-m-d');
    //         $end = Carbon::createFromFormat('d/m/Y', $request->enddate)->format('Y-m-d');
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Format tanggal tidak valid'], 422);
    //     }
    //     $authUser = Auth::user();

    //     $data = Absensi::with('user', 'jenis')->whereBetween('created_at', [$start, $end]);

    //     // Jika request user NULL, tampilkan data milik user sendiri
    //     if ($authUser->hasRole('ADM') && is_null($request->user)) {
    //         $data = $data->get();
    //     } elseif ($authUser->hasRole('ADM')) {
    //         $user = User::where('username', $request->user)->firstOrFail();
    //         $data = $data->where('user_id', $user->id)->get();
    //     } elseif (is_null($request->user)) {
    //         $data = $data->where('user_id', Auth::user()->id)->get();
    //     } else {
    //         // Ambil data user yang akan dilihat
    //         $user = User::where('username', $request->user)->firstOrFail();

    //         // Jika user adalah SPV dan satu tim, atau user adalah admin, izinkan akses
    //         if (
    //             ($authUser->hasPermissionTo('view other absen') && $authUser->team_id === $user->team_id) ||
    //             $authUser->hasRole('ADM')
    //         ) {
    //             $data = $data->where('user_id', $user->id)->get();
    //         } else {
    //             return response()->json(['message' => 'Forbidden'], 403);
    //         }
    //     }

    //     return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function($row) use ($authUser) {
    //                     $btn = '
    //                         <a href="'. route('absen.show', $row->id) .'" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
    //                             <i class="fa fa-eye"></i>&nbsp;Show
    //                         </a>
    //                     ';
    //                     if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('edit absen')) || $authUser->hasRole('ADM')) {
    //                         $btn .= '
    //                             <a href="'. route('absen.edit', $row->id) .'" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
    //                                 <i class="fa fa-edit"></i>&nbsp;Edit
    //                             </a>
    //                         ';
    //                     }
    //                     if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('delete absen')) || $authUser->hasRole('ADM')) {
    //                         $btn .= '
    //                             <button type="button" data-id="'. $row->id .'" title="Hapus absen" class="btn btn-link btn-danger btn-destroy" data-original-title="Remove">
    //                                 <i class="fa fa-times"></i>&nbsp;Hapus
    //                             </button>
    //                         ';
    //                     }
    //                     return '
    //                         <div class="form-button-action">
    //                             <div class="btn-group dropdown">
    //                                 <button class="btn btn-icon btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
    //                                     <i class="fa fa-align-left"></i>
    //                                 </button>
    //                                 <ul class="dropdown-menu" role="menu">
    //                                     '.$btn.'
    //                                 </ul>
    //                             </div>
    //                         </div>
    //                     ';
    //             })
    //             ->addColumn('tanggal', function ($row){
    //                 return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
    //             })
    //             ->editColumn('picture', function ($row){
    //                 return '<a href="'. asset('upload/absen/'.$row->picture) .'" target="_blank"><img src="'. asset('upload/absen/'.$row->picture) .'" alt="foto absen" width="50" height="50"></a>';
    //             })
    //             ->addColumn('short_lokasi', function ($row){
    //                 return Str::limit($row->location, 50, '...');
    //             })
    //             ->rawColumns(['action', 'picture', 'created_at', 'short_lokasi'])
    //             ->make(true);
    // }

    public function get_data(Request $request)
    {
        $authUser = Auth::user();

        // 1. Validasi dan parsing tanggal
        [$start, $end] = $this->parseDateRange($request);

        // 2. Tentukan user yang datanya akan diambil
        $targetUserId = $this->resolveTargetUserId($request, $authUser);

        // 3. Ambil data absensi
        $data = Absensi::with(['user', 'jenis'])
            ->whereBetween('created_at', [$start, $end])
            ->when($targetUserId !== null, fn($query) => $query->where('user_id', $targetUserId))
            ->get();

        // 4. Kembalikan response datatables
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->buildActionButtons($row, $authUser))
            ->addColumn('tanggal', fn($row) => Carbon::parse($row->created_at)->format('d-m-Y H:i:s'))
            ->editColumn('picture', fn($row) => $this->renderPicture($row->picture))
            ->addColumn('short_lokasi', fn($row) => Str::limit($row->location, 50, '...'))
            ->rawColumns(['action', 'picture', 'short_lokasi'])
            ->make(true);
    }

    private function parseDateRange(Request $request): array
    {
        try {
            $start = Carbon::createFromFormat('d/m/Y', $request->startdate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y', $request->enddate)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(422, 'Format tanggal tidak valid');
        }

        return [$start, $end];
    }

    private function resolveTargetUserId(Request $request, $authUser): ?int
    {
        $isADM = $authUser->hasRole('ADM');

        if (is_null($request->user)) {
            return $isADM ? null : $authUser->id;
        }

        $user = User::where('username', $request->user)->firstOrFail();

        if (
            ($authUser->hasPermissionTo('view other absen') && $authUser->team_id === $user->team_id) ||
            $isADM
        ) {
            return $user->id;
        }

        abort(403, 'Forbidden');
    }

    private function buildActionButtons($row, $authUser): string
    {
        $buttons = '';

        $buttons .= '<a href="'. route('absen.show', $row->id) .'" class="btn btn-link btn-primary"><i class="fa fa-eye"></i> Show</a>';

        if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('edit absen')) || $authUser->hasRole('ADM')) {
            $buttons .= '<a href="'. route('absen.edit', $row->id) .'" class="btn btn-link btn-warning"><i class="fa fa-edit"></i> Edit</a>';
        }

        if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('delete absen')) || $authUser->hasRole('ADM')) {
            $buttons .= '<button type="button" data-id="'. $row->id .'" class="btn btn-link btn-danger btn-destroy"><i class="fa fa-times"></i> Hapus</button>';
        }

        return '
            <div class="form-button-action">
                <div class="btn-group dropend">
                    <button class="btn btn-icon btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-align-left"></i>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        '.$buttons.'
                    </ul>
                </div>
            </div>
        ';
    }

    private function renderPicture($filename): string
    {
        $url = asset('upload/absen/' . $filename);
        return "<a href='{$url}' target='_blank'><img src='{$url}' alt='foto absen' width='50' height='50'></a>";
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisAbsen = JenisAbsen::get();
        $data = [
            'jenisAbsen' => $jenisAbsen
        ];
        return view('aktivitas.absen.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'picture' => ['required', 'image'],
            'location' => ['required'],
        ], [
            'picture.required' => 'Izinkan akses lokasi untuk absen',
            'required' => ':attribute wajib diisi',
            'image' => ':attribute harus berupa file',
        ], [
            'jenis' => 'Jenis Absen',
            'picture' => 'Foto',
        ]);

        $image = $request->file('picture');
        $imageName = time().'.jpeg';
        $destinationPath = public_path('upload/absen/'.$imageName);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);
        $image->toJpeg(60)->save($destinationPath);

        Absensi::create([
            'user_id' => Auth::user()->id,
            'jenis_absen_id' => $request->jenis,
            'picture' => $imageName,
            'location' => $request->location,
        ]);

        return redirect()->route('absen.index')->with('success', 'Berhasil melakukan absensi');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $absen = Absensi::findOrFail($id);
        $authUser = Auth::user();
        /*
            admin dapat melihat detail
            spv dapat melihat detail jika satu team
            opr bisa melihat detail jika milik dia sendiri
        */
        if ($authUser->hasRole('OPR') && $authUser->id != $absen->user_id) {
            abort(403);
        } elseif ($authUser->hasRole('SPV') && $authUser->team_id != $absen->user->team_id) {
            abort(403);
        }

        return view('aktivitas.absen.show', compact('absen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absen = Absensi::findOrFail($id);
        if (Auth::user()->id != $absen->user_id && !Auth::user()->hasRole('ADM')) {
            abort(403);
        }
        $jenisAbsen = JenisAbsen::get();
        $data = [
            'absen' => $absen,
            'jenisAbsen' => $jenisAbsen,
        ];
        return view('aktivitas.absen.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'jenis' => 'required',
            'picture' => ['nullable', 'image'],
        ], [
            'picture.required' => 'Izinkan akses lokasi untuk absen',
            'required' => ':attribute wajib diisi',
            'image' => ':attribute harus berupa file',
        ], [
            'jenis' => 'Jenis Absen',
            'picture' => 'Foto',
        ]);

        $absen = Absensi::findOrFail($id);
        if (Auth::user()->id != $absen->user_id && !Auth::user()->hasRole('ADM')) {
            abort(403);
        }

        $dataUpdate = [
            'jenis_absen_id' => $request->jenis,
        ];

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $imageName = time().'.jpeg';
            $destinationPath = public_path('upload/absen/'.$imageName);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->toJpeg(80)->save($destinationPath);
            unlink(public_path('upload/absen/'.$absen->picture));

            $dataUpdate['picture'] = $imageName;
        }

        $absen->update($dataUpdate);

        return redirect()->route('absen.index')->with('success', 'Data absensi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absen = Absensi::findOrFail($id);
        if (Auth::user()->id != $absen->user_id && !Auth::user()->hasRole('ADM')) {
            return response()->json(['status' => 'error', 'message' => 'You don\'t have permission to access this resource'], 403);
        }
        $picture_path = public_path('upload/absen/'.$absen->picture);
        if (\File::exists($picture_path)) {
            unlink($picture_path);
        }
        $absen->delete();
        return response()->json(['status' => 'success', 'message' => 'Absensi Berhasil dihapus'], 200);
    }
}
