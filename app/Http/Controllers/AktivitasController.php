<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\CaraAktivitas;
use App\Models\Rekanan;
use App\Models\TipeAktivitas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DataTables;

class AktivitasController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view aktivitas|view other aktivitas')->only(['index', 'get_data', 'show', 'download', 'cetak']);
        $this->middleware('permission:add aktivitas')->only(['create', 'store', 'generateFilename']);
        $this->middleware('permission:edit aktivitas')->only(['edit', 'update', 'generateFilename']);
        $this->middleware('permission:delete aktivitas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [];
        if (Auth::user()->hasRole('ADM')) {
            $data['users'] = User::get();
        } elseif (Auth::user()->hasPermissionTo('view other aktivitas')) {
            $data['users'] = User::where('team_id', Auth::user()->team_id)->get();
        }
        return view('aktivitas.aktivitas.index', $data);
    }

    public function get_data(Request $request)
    {
        $authUser = Auth::user();
        $data = Aktivitas::with('user', 'rekanan', 'tipeAktivitas', 'caraAktivitas');
        if (!is_null($request->startdate) && !is_null($request->enddate)) {
            // dd($request->input());
            $start = Carbon::createFromFormat('d/m/Y', $request->startdate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y', $request->enddate)->format('Y-m-d');
            $data = $data->whereBetween('tanggal', [$start, $end]);
        }

        if ($authUser->hasRole('ADM') && is_null($request->user)) {
            $data = $data->get();
        } elseif ($authUser->hasRole('ADM')) {
            $user = User::where('username', $request->user)->firstOrFail();
            $data = $data->where('user_id', $user->id)->get();
        } elseif (is_null($request->user)) {
            // Jika request user NULL, tampilkan data milik user sendiri
            $data = $data->where('user_id', Auth::user()->id)->get();
        } else {
            // Ambil data user yang akan dilihat
            $user = User::where('username', $request->user)->firstOrFail();

            // Jika user adalah SPV dan satu tim, atau user adalah admin, izinkan akses
            if (
                ($authUser->hasPermissionTo('view other aktivitas') && $authUser->team_id === $user->team_id) ||
                $authUser->hasRole('ADM')
            ) {
                $data = $data->where('user_id', $user->id)
                    ->get();
            } else {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($authUser) {
                $btn = '
                            <a href="' . route('aktivitas.show', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('edit aktivitas')) || $authUser->hasRole('ADM')) {
                    $btn .= '
                                <a href="' . route('aktivitas.edit', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>&nbsp;Edit
                                </a>
                            ';
                }
                if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('delete aktivitas')) || $authUser->hasRole('ADM')) {
                    $btn .= '
                                <button type="button" data-id="' . $row->id . '" title="Hapus aktivitas" class="btn btn-link btn-danger btn-destroy" data-original-title="Remove">
                                    <i class="fa fa-times"></i>&nbsp;Hapus
                                </button>
                            ';
                }
                return '
                            <div class="form-button-action">
                                <div class="btn-group dropdown">
                                    <button class="btn btn-icon btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fa fa-align-left"></i>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        ' . $btn . '
                                    </ul>
                                </div>
                            </div>
                        ';
            })
            ->addColumn('tanggal_show', function ($row) {
                return Carbon::parse($row->tanggal)->format('d/m/Y');
            })
            ->editColumn('file', function ($row) {
                $file = '';
                if (!is_null($row->file) && Storage::disk('local')->exists('aktivitas/' . $row->file)) {
                    $file = '<a href="' . route('aktivitas.file', $row->id) . '" target="_blank" class="btn btn-info btn-sm">'.$row->file.'</a>';
                }
                return $file;
            })
            ->addColumn('short_rencana', function ($row) {
                return $row->rencana ? Str::limit($row->rencana, 50) : '';
            })
            ->addColumn('short_aktivitas', function ($row) {
                return $row->aktivitas ? Str::limit($row->aktivitas, 50) : '';
            })
            ->addColumn('short_hasil', function ($row) {
                return $row->hasil ? Str::limit($row->hasil, 50) : '';
            })
            ->rawColumns(['action', 'tanggal_show', 'file', 'short_rencana', 'short_aktivitas', 'short_hasil'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rekanan = Rekanan::get();
        $tipe = TipeAktivitas::get();
        $cara = CaraAktivitas::get();
        $data = [
            'rekanans' => $rekanan,
            'tipes' => $tipe,
            'caras' => $cara,
        ];
        return view('aktivitas.aktivitas.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'string'],
            'jam_awal' => ['required', 'string'],
            'jam_akhir' => ['required', 'string'],
            'rencana' => ['nullable', 'string'],
            'aktivitas' => ['nullable', 'string'],
            'hasil' => ['nullable', 'string'],
            'rekan_id' => ['nullable', 'exists:rekanans,id'],
            'tipe_id' => ['nullable', 'exists:tipe_aktivitas,id'],
            'cara_id' => ['nullable', 'exists:cara_aktivitas,id'],
        ], [
            'required' => ':attribute wajib diisi',
            'date' => ':attribute harus berupa tanggal',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar didatabase',
        ], [
            'jam_awal' => 'jam awal',
            'jam_akhir' => 'jam akhir',
            'rekan_id' => 'rekanan',
            'tipe_id' => 'tipe',
            'cara_id' => 'cara',
        ]);

        $create = [
            'user_id' => Auth::user()->id,
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'rencana' => $request->rencana,
            'aktivitas' => $request->aktivitas,
            'hasil' => $request->hasil,
            'rekan_id' => $request->rekan_id,
            'tipe_id' => $request->tipe_id,
            'cara_id' => $request->cara_id,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $this->generateFilename() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('aktivitas', $fileName, 'local');
            $create['file'] = $fileName;
        }

        Aktivitas::create($create);

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        $authUser = Auth::user();
        /*
            admin dapat melihat detail
            spv dapat melihat detail jika satu team
            opr bisa melihat detail jika milik dia sendiri
        */
        if ($authUser->hasRole('OPR') && $authUser->id != $aktivitas->user_id) {
            abort(403);
        } elseif ($authUser->hasRole('SPV') && $authUser->team_id != $aktivitas->user->team_id) {
            abort(403);
        }

        return view('aktivitas.aktivitas.show', compact('aktivitas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        if (Auth::user()->id != $aktivitas->user_id && !Auth::user()->hasRole('ADM')) {
            abort(403);
        }
        $rekanan = Rekanan::get();
        $tipe = TipeAktivitas::get();
        $cara = CaraAktivitas::get();
        $data = [
            'aktivitas' => $aktivitas,
            'rekanans' => $rekanan,
            'tipes' => $tipe,
            'caras' => $cara,
        ];
        return view('aktivitas.aktivitas.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => ['required', 'string'],
            'jam_awal' => ['required', 'string'],
            'jam_akhir' => ['required', 'string'],
            'rencana' => ['nullable', 'string'],
            'aktivitas' => ['nullable', 'string'],
            'hasil' => ['nullable', 'string'],
            'rekan_id' => ['nullable', 'exists:rekanans,id'],
            'tipe_id' => ['nullable', 'exists:tipe_aktivitas,id'],
            'cara_id' => ['nullable', 'exists:cara_aktivitas,id'],
        ], [
            'required' => ':attribute wajib diisi',
            'date' => ':attribute harus berupa tanggal',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar didatabase',
        ], [
            'jam_awal' => 'jam awal',
            'jam_akhir' => 'jam akhir',
            'rekan_id' => 'rekanan',
            'tipe_id' => 'tipe',
            'cara_id' => 'cara',
        ]);


        $aktivitas = Aktivitas::findOrFail($id);
        if (Auth::user()->id !== $aktivitas->user_id && !Auth::user()->hasRole('ADM')) {
            abort(403);
        }

        $dataUpdate = [
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'rencana' => $request->rencana,
            'aktivitas' => $request->aktivitas,
            'hasil' => $request->hasil,
            'rekan_id' => $request->rekan_id,
            'tipe_id' => $request->tipe_id,
            'cara_id' => $request->cara_id,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $this->generateFilename() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('aktivitas', $fileName, 'local');
            $dataUpdate['file'] = $fileName;
            if (Storage::disk('local')->exists('aktivitas/' . $aktivitas->file)) {
                Storage::disk('local')->delete('aktivitas/' . $aktivitas->file);
            }
        }

        $aktivitas->update($dataUpdate);

        return redirect()->route('aktivitas.index')->with('success', 'Data aktivitas berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        if (Auth::user()->id != $aktivitas->user_id && !Auth::user()->hasRole('ADM')) {
            abort(403);
        }
        if (Storage::disk('local')->exists('aktivitas/' . $aktivitas->file)) {
            Storage::disk('local')->delete('aktivitas/' . $aktivitas->file);
        }
        $aktivitas->delete();
        return response()->json(['status' => 'success', 'message' => 'Aktivitas Berhasil dihapus'], 200);
    }

    public function download(Request $request, $id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        $authUser = Auth::user();

        if ($authUser->hasRole('OPR') && $authUser->id != $aktivitas->user_id) {
            abort(403);
        } elseif ($authUser->hasRole('SPV') && $authUser->team_id != $aktivitas->user->team_id) {
            abort(403);
        }

        $path = storage_path("app/private/aktivitas/{$aktivitas->file}");

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function cetak(Request $request)
    {
        $authUser = Auth::user();
        $aktivitas = Aktivitas::with('user', 'rekanan', 'tipeAktivitas', 'caraAktivitas');

        // Jika request startdate dan enddate NULL, ambil tanggal hari ini
        $start = is_null($request->startdate) ? Carbon::now()->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $request->startdate)->format('Y-m-d');
        $end = is_null($request->enddate) ? Carbon::now()->format('Y-m-d') : Carbon::createFromFormat('d/m/Y', $request->enddate)->format('Y-m-d');

        $aktivitas = $aktivitas->whereBetween('tanggal', [$start, $end]);

        // Jika request user NULL, tampilkan data milik user sendiri
        if (is_null($request->user)) {
            $aktivitas = $aktivitas->where('user_id', Auth::user()->id);
            $user = $authUser;
        } else {
            // Ambil data user yang akan dilihat
            $user = User::where('username', $request->user)->firstOrFail();

            // Jika user adalah SPV dan satu tim, atau user adalah admin, izinkan akses
            if (
                ($authUser->hasPermissionTo('view other aktivitas') && $authUser->team_id === $user->team_id) ||
                $authUser->hasRole('ADM')
            ) {
                $aktivitas = $aktivitas->where('user_id', $user->id);
            } else {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $aktivitas = $aktivitas->orderBy('tanggal', 'desc')->orderBy('jam_awal')->get();

        $data = [
            'start' => $start,
            'end' => $end,
            'user' => $user,
            'aktivitas' => $aktivitas,
        ];

        return view('aktivitas.aktivitas.cetak', $data);
    }

    private function generateFilename()
    {
        $filename = Str::random(15);
        if (Storage::disk('local')->exists('aktivitas/' . $filename)) {
            $filename = generateFilename();
        }

        return $filename;
    }
}
