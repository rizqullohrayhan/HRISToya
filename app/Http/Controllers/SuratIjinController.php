<?php

namespace App\Http\Controllers;

use App\Models\PJSSuratIjin;
use App\Models\SuratIjin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SuratIjinController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view surat ijin')->only(['index', 'data', 'show', 'cetak', 'otoritas']);
        $this->middleware('permission:add surat ijin')->only(['create', 'store', 'otoritas']);
        $this->middleware('permission:edit surat ijin')->only(['edit', 'update', 'otoritas', 'allowUpdate']);
        $this->middleware('permission:delete surat ijin')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('surat-ijin.index');
    }

    public function data(Request $request)
    {
        $tahun = $request->tahun ? $request->tahun : date('Y');
        $authUser = Auth::user();
        if ($authUser->hasRole('ADM')) {
            $surat = SuratIjin::with(['user'])
                    ->whereYear('tgl_awal', $tahun)
                    ->get();
        } else {
            $teamId = $authUser->team_id;
            $surat = SuratIjin::with(['user'])
                    ->whereYear('tgl_awal', $tahun)
                    ->whereHas('user', function ($query) use ($teamId) {
                        $query->where('team_id', $teamId);
                    })
                    ->get();
        }

        return DataTables::of($surat)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($authUser) {
                $isAdmin = $authUser->hasRole('ADM');
                $isOwnerOrCreator = $authUser->id == $row->dibuat_id || $authUser->id == $row->created_by;
                $isEditable = is_null($row->diperiksa_at);
                $btn = '
                            <a href="' . route('ijin.show', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('edit surat ijin') && $isOwnerOrCreator && $isEditable)
                ) {
                    $btn .= '
                                <a href="' . route('ijin.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>&nbsp;Edit
                                </a>
                            ';
                }
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('delete surat ijin') && $isOwnerOrCreator && $isEditable)
                ) {
                    $btn .= '
                                <button type="button" data-id="' . $row->id . '" title="Hapus" class="btn btn-link btn-danger btn-destroy" data-original-title="Remove">
                                    <i class="fa fa-times"></i>&nbsp;Hapus
                                </button>
                            ';
                }
                return '
                        <div class="form-button-action">
                            <div class="btn-group dropend">
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
            ->editColumn('tanggal', function ($row) {
                $tgl_awal = Carbon::parse($row->tgl_awal)->format('d/m/Y');
                $tgl_akhir = Carbon::parse($row->tgl_akhir)->format('d/m/Y');
                if ($tgl_awal == $tgl_akhir) {
                    $return = $tgl_awal;
                } else {
                    $return = $tgl_awal .' - '. $tgl_akhir;
                }

                return $return;
            })
            ->addColumn('jam', function ($row) {
                $jam_awal = Carbon::parse($row->tgl_awal)->format('H:i');
                $jam_akhir = Carbon::parse($row->tgl_akhir)->format('H:i');
                return $jam_awal .' - '. $jam_akhir;
            })
            ->rawColumns(['action', 'tanggal', 'jam'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pjs = User::all();
        $data = [
            'pjs' => $pjs,
        ];
        return view('surat-ijin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            // 'tanggal' => ['required', 'string'],
            'tgl_awal' => ['required', 'string'],
            'tgl_akhir' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
            'penganti_id' => ['nullable', 'exists:users,id'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'tugas' => ['required', 'array', 'min:1'],
            'tugas.*.name' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'user_id' => 'Nama',
            'tgl_awal' => 'Tanggal Awal',
            'tgl_akhir' => 'Tanggal Akhir',
            'penganti_id' => 'Nama PJS',
            'tugas' => 'Pelimpahan Tugas',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
        ]);

        $no_surat = DB::transaction(function () use ($request) {
            $datePart = Carbon::createFromFormat('d/m/Y H:i', $request->tgl_awal)->format('ym');
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('surat_ijins')
                            ->lockForUpdate()
                            ->where('no_surat', 'LIKE', "TIM.IJ.250.$datePart.%")
                            ->latest('id')
                            ->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_surat, -5); // Ambil 5 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return "TIM.IJ.250.$datePart.$newNumber";
        });

        $surat = SuratIjin::create([
            'user_id' => $request->user_id,
            'no_surat' => $no_surat,
            'tgl_awal' => Carbon::createFromFormat('d/m/Y H:i', $request->tgl_awal),
            'tgl_akhir' => Carbon::createFromFormat('d/m/Y H:i', $request->tgl_akhir),
            // 'jam_awal' => $request->jam_awal,
            // 'jam_akhir' => $request->jam_akhir,
            'keperluan' => $request->keperluan,
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

        foreach ($validatedData['tugas'] as $tugas) {
            PJSSuratIjin::create([
                'surat_id' => $surat->id,
                'penganti_id' => $request->penganti_id,
                'tugas' => $tugas['name'],
            ]);
        }

        return redirect()->route('ijin.show', $surat->id)->with('success', "Surat Ijin $no_surat berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = SuratIjin::with(['status', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'pjs'])
                            ->where('id', $id)
                            ->firstOrFail();
        return view('surat-ijin.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = SuratIjin::with(['status', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'pjs'])
                            ->where('id', $id)
                            ->firstOrFail();

        $response = $this->allowUpdate($surat);
        if ($response) return $response;

        $pjs = User::all();
        $data = [
            'surat' => $surat,
            'pjs' => $pjs,
        ];
        return view('surat-ijin.edit', $data);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'tgl_awal' => ['required', 'string'],
            'tgl_akhir' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
            'penganti_id' => ['nullable', 'exists:users,id'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'tugas' => ['required', 'array', 'min:1'],
            'tugas.*.name' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'user_id' => 'Nama',
            'tgl_awal' => 'Tanggal Awal',
            'tgl_akhir' => 'Tanggal Akhir',
            'penganti_id' => 'Nama PJS',
            'tugas' => 'Pelimpahan Tugas',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
        ]);

        $surat = SuratIjin::findOrFail($id);

        $response = $this->allowUpdate($surat);
        if ($response) return $response;

        $surat->update([
            'user_id' => $request->user_id,
            'tgl_awal' => Carbon::createFromFormat('d/m/Y H:i', $request->tgl_awal),
            'tgl_akhir' => Carbon::createFromFormat('d/m/Y H:i', $request->tgl_akhir),
            'keperluan' => $request->keperluan,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diperiksa_id' => $request->diperiksa_by,
            'diperiksa_at' => $request->diperiksa_at ? Carbon::parse($request->diperiksa_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
        ]);

        // Ambil semua ID PJS Surat Ijin yang ada di database
        $existingPJSIds = PJSSuratIjin::where('surat_id', $surat->id)->pluck('id')->toArray();

        // Ambil semua ID pjs yang dikirim dari form (hanya ID yang sudah ada)
        $formPJSIds = collect($request->tugas)->pluck('id')->filter()->toArray();

        // Cari data yang harus dihapus (ID di database tapi tidak ada di form)
        $toDelete = array_diff($existingPJSIds, $formPJSIds);
        PJSSuratIjin::whereIn('id', $toDelete)->delete();

        // Loop data tugas dari form
        foreach ($request->tugas as $tugas) {
            if (isset($tugas['id'])) {
                // Jika ID ada di form, update detail lama
                PJSSuratIjin::where('id', $tugas['id'])->update([
                    'penganti_id' => $request->penganti_id,
                    'tugas' => $tugas['name'],
                ]);
            } else {
                // Jika ID tidak ada di form, buat detail baru
                PJSSuratIjin::create([
                    'surat_id' => $surat->id,
                    'penganti_id' => $request->penganti_id,
                    'tugas' => $tugas['name'],
                ]);
            }
        }

        return redirect()->route('ijin.show', $surat->id)->with('success', "Surat Ijin berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surat = SuratIjin::findOrFail($id);
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

        $surat = SuratIjin::findOrFail($id);
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
        $surat = SuratIjin::with(['status', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'pjs'])
                            ->where('id', $id)
                            ->firstOrFail();
        $user = Auth::user();
        if (is_null($surat->disetujui_at) || ($user->id != $surat->dibuat_id && !$user->hasRole('ADM'))) {
            return redirect()->back()->with('error', 'Tidak dapat mencetak surat');
        }
        return view('surat-ijin.print', compact('surat'));
    }
}
