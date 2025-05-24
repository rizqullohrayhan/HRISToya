<?php

namespace App\Http\Controllers;

use App\Models\NotulenRapat;
use App\Models\UraianNotulenRapat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class NotulenRapatController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view notulen rapat')->only(['index', 'data', 'show', 'otoritas', 'cetak']);
        $this->middleware('permission:add notulen rapat')->only(['create', 'store', 'otoritas']);
        $this->middleware('permission:edit notulen rapat')->only(['edit', 'update', 'otoritas', 'allowUpdate']);
        $this->middleware('permission:delete notulen rapat')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'start' => Carbon::now()->startOfMonth()->format('d/m/Y'),
            'end' => Carbon::now()->endOfMonth()->format('d/m/Y'),
        ];
        return view('notulen_rapat.index', $data);
    }

    public function data(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);
        $authUser = Auth::user();

        $notulenRapat = NotulenRapat::whereBetween('tanggal', [$start, $end]);

        if ($authUser->hasRole('ADM')) {
            $notulenRapat = $notulenRapat->get();
        } else {
            $notulenRapat = $notulenRapat->where(function ($query) use ($authUser) {
                $query->whereHas('daftarHadir', function ($q) use ($authUser) {
                    $q->where('user_id', $authUser->id);
                })->orWhere('created_by', $authUser->id);
            })->get();
        }

        return datatables()->of($notulenRapat)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->buildActionButtons($row, $authUser))
            ->editColumn('tanggal', fn($row) => $row->tanggal ? Carbon::parse($row->tanggal)->format('d/m/Y H:i') : '')
            ->rawColumns(['action', 'tanggal'])
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

    private function buildActionButtons($row, $authUser): string
    {
        $isAdmin = $authUser->hasRole('ADM');
        $isOwnerOrCreator = $authUser->id == $row->dibuat_id || $authUser->id == $row->created_by;
        $isEditable = is_null($row->diperiksa_at);
        $buttons = '';

        $buttons .= '<a href="'. route('notulen_rapat.show', $row->id) .'" class="btn btn-link btn-primary"><i class="fa fa-eye"></i> Show</a>';

        if ($isAdmin || ($authUser->can('edit notulen rapat') && $isOwnerOrCreator && $isEditable)) {
            $buttons .= '<a href="'. route('notulen_rapat.edit', $row->id) .'" class="btn btn-link btn-warning"><i class="fa fa-edit"></i> Edit</a>';
        }

        if ($isAdmin || ($authUser->can('delete notulen rapat') && $isOwnerOrCreator && $isEditable)) {
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $data = [
            'users' => $users,
        ];
        return view('notulen_rapat.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->input());
        $request->validate([
            'tanggal' => ['required'],
            'agenda' => ['required', 'string'],
            'unit_kerja' => ['required', 'string'],
            'pimpinan' => ['required', 'string'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'detail' => ['nullable', 'array', 'min:1'],
            'detail.*.uraian' => ['required', 'string'],
            'detail.*.action' => ['nullable', 'string'],
            'detail.*.due_date' => ['nullable', 'string'],
            'detail.*.pic' => ['nullable', 'string'],
            'hadir' => ['required', 'array', 'min:1'],
            'hadir.*.user_id' => ['nullable', 'exists:users,id'],
            'hadir.*.nama' => ['nullable', 'string', 'max:255'],
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'agenda.required' => 'Agenda harus diisi',
            'unit_kerja.required' => 'Unit Kerja harus diisi',
            'pimpinan.required' => 'Pimpinan harus diisi',
            'detail.*.uraian.required' => 'Uraian harus diisi',
            'hadir.*.uraian.required' => 'Uraian harus diisi',
        ]);

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $imageName = time() . '.jpeg';
            $destinationPath = 'foto_hadir_rapat/' . $imageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);

            Storage::disk('local')->put($destinationPath, $image->encodeByExtension('jpeg', 20));
        }

        $no_surat = DB::transaction(function () use ($request) {
            $datePart = Carbon::createFromFormat("d/m/Y H:i", $request->tanggal)->format('ym'); // contoh: 2504
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('notulen_rapats')
                            ->lockForUpdate()
                            ->where('no_surat', 'LIKE', "TIM.NR.220.$datePart.%")
                            ->latest('id')
                            ->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_surat, -5); // Ambil 5 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return "TIM.NR.220.$datePart.$newNumber";
        });

        $notulenRapat = \App\Models\NotulenRapat::create([
            'no_surat' => $no_surat,
            'tanggal' => Carbon::createFromFormat('d/m/Y H:i', $request->tanggal)->format('Y-m-d H:i'),
            'agenda' => $request->agenda,
            'agenda_plain' => strip_tags($request->agenda),
            'unit_kerja' => $request->unit_kerja,
            'pimpinan' => $request->pimpinan,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diperiksa_id' => $request->diperiksa_by,
            'diperiksa_at' => $request->diperiksa_at ? Carbon::parse($request->diperiksa_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'created_by' => Auth::user()->id,
        ]);

        $tipe = 1;
        foreach ($request->detail as $detail) {
            if (!is_null($detail['action']) || !is_null($detail['due_date']) || !is_null($detail['pic'])) {
                $tipe = 2;
            }
            UraianNotulenRapat::create([
                'notulen_rapat_id' => $notulenRapat->id,
                'uraian' => $detail['uraian'],
                'action' => $detail['action'],
                'due_date' => $detail['due_date'] ? Carbon::createFromFormat('d/m/Y', $detail['due_date'])->format('Y-m-d') : null,
                'pic' => $detail['pic'],
            ]);
        }
        $notulenRapat->update(['tipe' => $tipe]);
        $daftarHadirBaru = [];
        foreach ($request->hadir as $hadir) {
            if (!empty($hadir['user_id']) || !empty($hadir['nama'])) {
                $daftarHadirBaru[] = [
                    'user_id' => $hadir['user_id'] ?? null,
                    'nama' => $hadir['nama'] ?? null,
                ];
            }
        }
        if (count($daftarHadirBaru)) {
            $notulenRapat->daftarHadir()->createMany($daftarHadirBaru);
        }
        // $notulenRapat->daftarHadir()->createMany($request->hadir);
        return redirect()->route('notulen_rapat.show', $notulenRapat->id)->with('success', 'Notulen Rapat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notulenRapat = \App\Models\NotulenRapat::with(['uraian', 'daftarHadir', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui'])->findOrFail($id);
        return view('notulen_rapat.show', compact('notulenRapat'));
    }

    private function allowUpdate($surat)
    {
        $authUser = Auth::user();
        $isNotAdmin = !$authUser->hasRole('ADM');
        $isNotOwnerOrCreator = $authUser->id != $surat->dibuat_id && $authUser->id != $surat->created_by;
        $isAlreadyAcknowledged = !is_null($surat->diperiksa_at);
        if ($isNotAdmin && ($isNotOwnerOrCreator || $isAlreadyAcknowledged)) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengedit data ini');
        }
        return null;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $notulenRapat = \App\Models\NotulenRapat::with(['uraian', 'daftarHadir', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui'])->findOrFail($id);

        $response = $this->allowUpdate($notulenRapat);
        if ($response) return $response;

        $users = User::all();
        return view('notulen_rapat.edit', compact('notulenRapat', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'tanggal' => ['required'],
            'agenda' => ['required', 'string'],
            'unit_kerja' => ['required', 'string'],
            'pimpinan' => ['required', 'string'],
            'detail' => ['nullable', 'array', 'min:1'],
            'detail.*.uraian' => ['required', 'string'],
            'detail.*.action' => ['nullable', 'string'],
            'detail.*.due_date' => ['nullable', 'string'],
            'detail.*.pic' => ['nullable', 'string'],
            'hadir' => ['required', 'array', 'min:1'],
            'hadir.*.user_id' => ['nullable', 'exists:users,id'],
            'hadir.*.nama' => ['nullable', 'string', 'max:255'],
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'agenda.required' => 'Agenda harus diisi',
            'unit_kerja.required' => 'Unit Kerja harus diisi',
            'pimpinan.required' => 'Pimpinan harus diisi',
            'detail.*.uraian.required' => 'Uraian harus diisi',
            'hadir.*.uraian.required' => 'Uraian harus diisi',
        ]);

        $notulenRapat = \App\Models\NotulenRapat::findOrFail($id);
        $response = $this->allowUpdate($notulenRapat);
        if ($response) return $response;
        $notulenRapat->update([
            'tanggal' => Carbon::createFromFormat('d/m/Y H:i', $request->tanggal)->format('Y-m-d H:i'),
            'agenda' => $request->agenda,
            'agenda_plain' => strip_tags($request->agenda),
            'unit_kerja' => $request->unit_kerja,
            'pimpinan' => $request->pimpinan,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diperiksa_id' => $request->diperiksa_by,
            'diperiksa_at' => $request->diperiksa_at ? Carbon::parse($request->diperiksa_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
        ]);

        $tipe = 1;
        foreach ($request->detail as $detail) {
            if (!is_null($detail['action']) || !is_null($detail['due_date']) || !is_null($detail['pic'])) {
                $tipe = 2;
            }
            UraianNotulenRapat::updateOrCreate(
                ['id' => $detail['id']],
                [
                    'notulen_rapat_id' => $notulenRapat->id,
                    'uraian' => $detail['uraian'],
                    'action' => $detail['action'],
                    'due_date' => $detail['due_date'] ? Carbon::createFromFormat('d/m/Y', $detail['due_date'])->format('Y-m-d') : null,
                    'pic' => $detail['pic'],
                ]
            );
        }
        $notulenRapat->update(['tipe' => $tipe]);
        $notulenRapat->daftarHadir()->delete();
        $daftarHadirBaru = [];
        foreach ($request->hadir as $hadir) {
            if (!empty($hadir['user_id']) || !empty($hadir['nama'])) {
                $daftarHadirBaru[] = [
                    'user_id' => $hadir['user_id'] ?? null,
                    'nama' => $hadir['nama'] ?? null,
                ];
            }
        }
        if (count($daftarHadirBaru)) {
            $notulenRapat->daftarHadir()->createMany($daftarHadirBaru);
        }
        // $notulenRapat->daftarHadir()->createMany($request->hadir);
        return redirect()->route('notulen_rapat.show', $notulenRapat->id)->with('success', 'Notulen Rapat berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surat = NotulenRapat::findOrFail($id);
        $authUser = Auth::user();
        if (
            !$authUser->hasRole('ADM') &&
            (
                ($authUser->id != $surat->dibuat_id && $authUser->id != $surat->created_by) ||
                !is_null($surat->diperiksa_at)
            )
        ) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat menghapus data'], 403);
        }
        $surat->delete();
        return response()->json(['status' => 'success', 'message' => 'Surat Ijin Berhasil dihapus'], 200);
    }

    public function otoritas(Request $request, string $id)
    {
        $request->validate([
            'target' => ['required'],
            'aksi' => ['required', Rule::in(['otorisasi', 'hapus']),],
        ]);

        $surat = NotulenRapat::findOrFail($id);
        $field = $request->target;
        $update = [];
        $data = [];

        // Urutan field otorisasi yang harus diperiksa
        $authorizationOrder = [
            ['name' => 'Dibuat Oleh', 'field' => 'dibuat_at'],
            ['name' => 'Diperiksa', 'field' => 'diperiksa_at'],
            ['name' => 'Menyetujui', 'field' => 'disetujui_at'],
            ['name' => 'Mengetahui', 'field' => 'mengetahui_at'],
        ];

        // Cari posisi field yang sedang diotorisasi di dalam array urutan
        $currentField = $field . '_at';
        $fieldIndex = array_search($currentField, array_column($authorizationOrder, 'field'));

        if ($request->aksi == 'otorisasi') {
            // Cek apakah field sebelumnya sudah terisi, jika ada field sebelumnya
            if ($fieldIndex > 0) {
                $previousField = $authorizationOrder[$fieldIndex - 1];  // Field sebelumnya dalam urutan
                if (empty($surat[$previousField['field']])) {
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (".$previousField['name'].") belum terisi.", 400);
                }
            }

            // Cek apakah field sudah terisi
            if (!empty($surat[$field . '_at'])) {
                $currentFieldName = $authorizationOrder[$fieldIndex]['name'];
                return response()->json("Gagal otorisasi, Otorisasi $currentFieldName sudah terisi.", 400);
            }

            $user = Auth::user()->id;
            $time = Carbon::now()->format('Y-m-d H:i:s');
            $data['name'] = Auth::user()->name;
            $data['role'] = Auth::user()->roles->pluck('name')[0];
            $data['time'] = $time;
        } else {
            // Cek apakah field sebelumnya sudah terisi, jika ada field sebelumnya
            if ($fieldIndex < 3) {
                $nextField = $authorizationOrder[$fieldIndex + 1];  // Field sebelumnya dalam urutan
                if (!empty($surat[$nextField['field']])) {
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (".$nextField['name'].") telah terotorisasi.", 400);
                }
            }
            $user = null;
            $time = null;
        }

        $update[$field . "_id"] = $user;
        $update[$field . "_at"] = $time;

        $surat->update($update);

        return response()->json(['status' => 'success', 'message' => 'Otorisasi Surat Ijin Berhasil diupdate', 'data' => $data], 200);
    }

    public function cetak(string $id)
    {
        $surat = NotulenRapat::with(['uraian', 'daftarHadir', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui'])
                            ->where('id', $id)
                            ->firstOrFail();
        if (is_null($surat->disetujui_at) && Auth::user()->id != $surat->dibuat_id) {
            return redirect()->back()->with('error', 'Tidak dapat mencetak surat');
        }
        if ($surat->tipe == 1) {
            return view('notulen_rapat.cetak1', compact('surat'));
        } else {
            return view('notulen_rapat.cetak2', compact('surat'));
        }
    }
}
