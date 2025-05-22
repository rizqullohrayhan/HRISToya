<?php

namespace App\Http\Controllers;

use App\Models\DetailSuratTugasKeluar;
use App\Models\SuratTugasKeluar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SuratTugasKeluarController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view tugas keluar')->only(['index', 'data', 'show', 'cetak']);
        $this->middleware('permission:add tugas keluar')->only(['create', 'store', 'otoritas']);
        $this->middleware('permission:edit tugas keluar')->only(['edit', 'update', 'otoritas']);
        $this->middleware('permission:delete tugas keluar')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tugas-keluar.index');
    }

    public function data(Request $request)
    {
        $tahun = $request->tahun ? $request->tahun : date('Y');
        $authUser = Auth::user();
        if ($authUser->hasRole('ADM')) {
            $surat = SuratTugasKeluar::with(['penerima', 'pemberi'])
                    ->whereYear('tgl_awal', $tahun)
                    ->get();
        } else {
            $teamId = $authUser->team_id;
            $surat = SuratTugasKeluar::with(['penerima', 'pemberi'])
                    ->whereYear('tgl_awal', $tahun)
                    ->where(function ($query) use ($teamId, $authUser) {
                        $query->whereHas('penerima', function ($q) use ($teamId) {
                            $q->where('team_id', $teamId);
                        })->orWhere('pemberi_id', $authUser->id);
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
                            <a href="' . route('tugas-keluar.show', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('edit tugas keluar') && $isOwnerOrCreator && $isEditable)
                ) {
                    $btn .= '
                                <a href="' . route('tugas-keluar.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>&nbsp;Edit
                                </a>
                            ';
                }
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('delete tugas keluar') && $isOwnerOrCreator && $isEditable)
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
            ->editColumn('jam_awal', function ($row) {
                $jam_awal = Carbon::parse($row->tgl_awal)->format('H:i');
                $jam_akhir = Carbon::parse($row->tgl_akhir)->format('H:i');
                return $jam_awal .' - '. $jam_akhir;
            })
            ->rawColumns(['action', 'tanggal', 'jam_awal'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::get();

        $authorization = [
            'dibuat' => ['label' => 'Dibuat Oleh', 'disabled' => false],
            'diperiksa' => ['label' => 'Diperiksa', 'disabled' => true],
            'disetujui' => ['label' => 'Menyetujui', 'disabled' => true],
            'mengetahui' => ['label' => 'Mengetahui', 'disabled' => true],
        ];

        $data = [
            'users' => $user,
            'authorization' => $authorization,
        ];
        return view('tugas-keluar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'tgl_awal' => $request->filled('tgl_awal')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->tgl_awal)->format('Y-m-d H:i')
                : null,

            'tgl_akhir' => $request->filled('tgl_akhir')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->tgl_akhir)->format('Y-m-d H:i')
                : null,
        ]);

        $validatedData = $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'pemberi_id' => ['required', 'exists:users,id'],
            'tgl_awal' => ['required', 'date'],
            'tgl_akhir' => ['required', 'date', 'after_or_equal:tgl_awal'],
            'kendaraan' => ['required', 'string'],
            'no_polisi' => ['required', 'string'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.instansi' => ['required', 'string'],
            'details.*.menemui' => ['required', 'string'],
            'details.*.tujuan' => ['required', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'tgl_akhir.after_or_equal' => 'Tanggal Akhir harus sama atau setelah Tanggal Awal',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'user_id' => 'Penerima',
            'tgl_awal' => 'Tanggal Awal',
            'tgl_akhir' => 'Tanggal Akhir',
            'no_polisi' => 'No Polisi',
            'details' => 'Tujuan',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
            'details.*.instansi' => 'Instansi',
            'details.*.menemui' => 'Menemui',
            'details.*.tujuan' => 'Tujuan',
        ]);

        $no_surat = DB::transaction(function () use ($request) {
            $datePart = Carbon::parse($request->tgl_awal)->format('ym');
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('surat_tugas_keluars')
                        ->lockForUpdate()
                        ->where('no_surat', 'LIKE', "TIM.TL.270.$datePart.%")
                        ->latest('id')
                        ->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_surat, -5); // Ambil 5 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return "TIM.TL.270.$datePart.$newNumber";
        });

        $surat = SuratTugasKeluar::create([
            'penerima_id' => $request->penerima_id,
            'pemberi_id' => $request->pemberi_id,
            'no_surat' => $no_surat,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'kendaraan' => $request->kendaraan,
            'no_polisi' => $request->no_polisi,
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

        foreach ($validatedData['details'] as $tujuan) {
            DetailSuratTugasKeluar::create([
                'surat_id' => $surat->id,
                'instansi' => $tujuan['instansi'],
                'menemui' => $tujuan['menemui'],
                'tujuan' => $tujuan['tujuan'],
            ]);
        }

        return redirect()->route('tugas-keluar.show', $surat->id)->with('success', "Tugas Keluar $no_surat berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = SuratTugasKeluar::with(['penerima', 'pemberi', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        return view('tugas-keluar.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = SuratTugasKeluar::with(['penerima', 'pemberi', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();

        $response = $this->allowUpdate($surat);
        if ($response) return $response;
        $user = User::get();

        $authorization = [
            'dibuat' => [
                'label' => 'Dibuat Oleh',
                'next' => 'diperiksa_at',
                'disabled_if' => false,
            ],
            'diperiksa' => [
                'label' => 'Diperiksa',
                'next' => 'disetujui',
                'disabled_if' => !$surat->dibuat_at,
            ],
            'disetujui' => [
                'label' => 'Menyetujui',
                'next' => 'mengetahui',
                'disabled_if' => !$surat->diperiksa_at,
            ],
            'mengetahui' => [
                'label' => 'Mengetahui',
                'next' => null,
                'disabled_if' => !$surat->disetujui_at,
            ],
        ];

        $data = [
            'surat' => $surat,
            'users' => $user,
            'authorization' => $authorization,
        ];
        return view('tugas-keluar.edit', $data);
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
        $request->merge([
            'tgl_awal' => $request->filled('tgl_awal')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->tgl_awal)->format('Y-m-d H:i')
                : null,

            'tgl_akhir' => $request->filled('tgl_akhir')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->tgl_akhir)->format('Y-m-d H:i')
                : null,
        ]);

        $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'pemberi_id' => ['required', 'exists:users,id'],
            'tgl_awal' => ['required', 'date'],
            'tgl_akhir' => ['required', 'date', 'after_or_equal:tgl_awal'],
            'kendaraan' => ['required', 'string'],
            'no_polisi' => ['required', 'string'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.instansi' => ['required', 'string'],
            'details.*.menemui' => ['required', 'string'],
            'details.*.tujuan' => ['required', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'date' => 'format :attribute tidak sesuai',
            'tgl_akhir.after_or_equal' => 'Tanggal Akhir harus sama atau setelah Tanggal Awal',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'user_id' => 'Penerima',
            'tgl_awal' => 'Tanggal Awal',
            'tgl_akhir' => 'Tanggal Akhir',
            'no_polisi' => 'No Polisi',
            'details' => 'Tujuan',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
        ]);

        $surat = SuratTugasKeluar::findOrFail($id);

        $response = $this->allowUpdate($surat);
        if ($response) return $response;

        $surat->update([
            'penerima_id' => $request->penerima_id,
            'pemberi_id' => $request->pemberi_id,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'kendaraan' => $request->kendaraan,
            'no_polisi' => $request->no_polisi,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diperiksa_id' => $request->diperiksa_by,
            'diperiksa_at' => $request->diperiksa_at ? Carbon::parse($request->diperiksa_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
        ]);

        // Ambil semua ID Detail Surat Ijin yang ada di database
        $existingDetailIds = DetailSuratTugasKeluar::where('surat_id', $surat->id)->pluck('id')->toArray();

        // Ambil semua ID Detail yang dikirim dari form (hanya ID yang sudah ada)
        $formDetailIds = collect($request->details)->pluck('id')->filter()->toArray();

        // Cari data yang harus dihapus (ID di database tapi tidak ada di form)
        $toDelete = array_diff($existingDetailIds, $formDetailIds);
        DetailSuratTugasKeluar::whereIn('id', $toDelete)->delete();

        // Loop data tujuan dari form
        foreach ($request->details as $tujuan) {
            if (isset($tujuan['id'])) {
                // Jika ID ada di form, update detail lama
                DetailSuratTugasKeluar::where('id', $tujuan['id'])->update([
                    'instansi' => $tujuan['instansi'],
                    'menemui' => $tujuan['menemui'],
                    'tujuan' => $tujuan['tujuan'],
                ]);
            } else {
                // Jika ID tidak ada di form, buat detail baru
                DetailSuratTugasKeluar::create([
                    'surat_id' => $surat->id,
                    'instansi' => $tujuan['instansi'],
                    'menemui' => $tujuan['menemui'],
                    'tujuan' => $tujuan['tujuan'],
                ]);
            }
        }

        return redirect()->route('tugas-keluar.show', $surat->id)->with('success', "Tugas Keluar berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $authUser = Auth::user();
        $surat = SuratTugasKeluar::findOrFail($id);
        /*
            - Admin? → silakan hapus, bebas.
            - Bukan admin? → harus pembuat, dan surat belum diterima.
        */
        if (
            !$authUser->hasRole('ADM') &&
            (
                ($authUser->id != $surat->dibuat_id && $authUser->id != $surat->created_by) ||
                !is_null($surat->diperiksa_at)
            )
        ) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat menghapus surat tugas'], 403);
        }
        $surat->delete();
        return response()->json(['status' => 'success', 'message' => 'Tugas Keluar Berhasil dihapus'], 200);
    }

    public function otoritas(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'target' => ['required'],
            'aksi' => ['required', Rule::in(['otorisasi', 'hapus']),],
        ]);

        $surat = SuratTugasKeluar::findOrFail($id);
        $field = $request->target;
        $update = [];

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
        } else {
            // Cek apakah field sebelumnya sudah terisi, jika ada field sebelumnya
            if ($fieldIndex < 3) {
                $nextField = $authorizationOrder[$fieldIndex + 1];  // Field sebelumnya dalam urutan
                if (!empty($surat[$nextField['field']])) {
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (".$nextField['name'].") telah terotorisasi.", 400);
                }
            }
            $user = $request->target == "penerima" ? $surat->penerima_id : null;
            $time = null;
        }

        $update[$field . "_id"] = $user;
        $update[$field . "_at"] = $time;

        $surat->update($update);

        return response()->json(['status' => 'success', 'message' => 'Otorisasi Tugas Keluar Berhasil diupdate'], 200);
    }

    public function cetak(string $id)
    {
        $surat = SuratTugasKeluar::with(['penerima', 'pemberi', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        $user = Auth::user();
        if (is_null($surat->disetujui_at) || ($user->id != $surat->dibuat_id && !$user->hasRole('ADM'))) {
            return redirect()->back()->with('error', 'Tidak dapat mencetak surat');
        }
        return view('tugas-keluar.cetak', compact('surat'));
    }
}
