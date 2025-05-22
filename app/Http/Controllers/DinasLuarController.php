<?php

namespace App\Http\Controllers;

use App\Models\DetailDinasLuar;
use App\Models\DinasLuar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DinasLuarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dinasluar.index');
    }

    public function data(Request $request)
    {
        $tahun = $request->tahun ? $request->tahun : date('Y');
        $authUser = Auth::user();
        if ($authUser->hasRole('ADM')) {
            $surat = DinasLuar::whereYear('berangkat', $tahun)
                    ->get();
        } else {
            $teamId = $authUser->team_id;
            $surat = DinasLuar::with(['user'])
                    ->whereYear('berangkat', $tahun)
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
                            <a href="' . route('dinasluar.show', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('edit dinas luar') && $isOwnerOrCreator && $isEditable)
                ) {
                    $btn .= '
                                <a href="' . route('dinasluar.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>&nbsp;Edit
                                </a>
                            ';
                }
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('delete dinas luar') && $isOwnerOrCreator && $isEditable)
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
            ->addColumn('jam', function ($row) {
                $berangkat = Carbon::parse($row->berangkat)->format('H:i');
                $kembali = Carbon::parse($row->kembali)->format('H:i');
                return $berangkat .' - '. $kembali;
            })
            ->rawColumns(['action', 'jam'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::get();
        $data = [
            'users' => $user,
        ];
        return view('dinasluar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'berangkat' => $request->filled('berangkat')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->berangkat)->format('Y-m-d H:i')
                : null,

            'kembali' => $request->filled('kembali')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->kembali)->format('Y-m-d H:i')
                : null,
        ]);

        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'tipe_kendaraan' => ['required', 'string'],
            'no_polisi' => ['required', 'string'],
            'berangkat' => ['required', 'date'],
            'kembali' => ['required', 'date', 'after_or_equal:tgl_awal'],
            'instansi' => ['required', 'string'],
            'nama_pejabat' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'no_telp' => ['required', 'string'],
            'tujuan' => ['required', 'string'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.deskripsi' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'kembali.after_or_equal' => 'Tanggal Kembali harus sama atau setelah Tanggal Berangkat',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'user_id' => 'Nama',
            'tipe_kendaraan' => 'Tipe Kendaraan',
            'no_polisi' => 'No Polisi',
            'berangkat' => 'Tanggal Berangkat',
            'kembali' => 'Tanggal Kembali',
            'instansi' => 'Instansi Tujuan',
            'nama_pejabat' => 'Nama Pejabat',
            'no_telp' => 'No Telepon',
            'tujuan' => 'Tujuan Dinas',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
            'details' => 'Rincian Hasil Dinas Luar',
            'details.*.deskripsi' => 'Rincian Hasil',
        ]);

        $no_surat = DB::transaction(function () use ($request) {
            $datePart = Carbon::parse($request->berangkat)->format('ym'); // contoh: 2504
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('dinas_luars')
                            ->lockForUpdate()
                            ->where('no_surat', 'LIKE', "TIM.DL.240.$datePart.%")
                            ->latest('id')
                            ->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_surat, -5); // Ambil 5 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return "TIM.DL.240.$datePart.$newNumber";
        });

        $surat = DinasLuar::create([
            'no_surat' => $no_surat,
            'user_id' => $request->user_id,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'no_polisi' => $request->no_polisi,
            'berangkat' => $request->berangkat,
            'kembali' => $request->kembali,
            'instansi' => $request->instansi,
            'nama_pejabat' => $request->nama_pejabat,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'tujuan' => $request->tujuan,
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

        foreach ($validatedData['details'] as $detail) {
            DetailDinasLuar::create([
                'surat_id' => $surat->id,
                'deskripsi' => $detail['deskripsi'],
            ]);
        }

        return redirect()->route('dinasluar.show', $surat->id)->with('success', "Dinas Luar $no_surat berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = DinasLuar::with(['user', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        return view('dinasluar.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = DinasLuar::with(['user', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        $response = $this->allowUpdate($surat);
        if ($response) return $response;
        if (Auth::user()->hasRole('ADM')) {
            $user = User::get();
        } else {
            $user = User::where('team_id', Auth::user()->team_id)->get();
        }
        $data = [
            'surat' => $surat,
            'users' => $user,
        ];
        return view('dinasluar.edit', $data);
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
            'berangkat' => $request->filled('berangkat')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->berangkat)->format('Y-m-d H:i')
                : null,

            'kembali' => $request->filled('kembali')
                ? Carbon::createFromFormat('d/m/Y H:i', $request->kembali)->format('Y-m-d H:i')
                : null,
        ]);

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'tipe_kendaraan' => ['required', 'string'],
            'no_polisi' => ['required', 'string'],
            'berangkat' => ['required', 'date'],
            'kembali' => ['required', 'date', 'after_or_equal:tgl_awal'],
            'instansi' => ['required', 'string'],
            'nama_pejabat' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'no_telp' => ['required', 'string'],
            'tujuan' => ['required', 'string'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.deskripsi' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'kembali.after_or_equal' => 'Tanggal Kembali harus sama atau setelah Tanggal Berangkat',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'pemohon_id' => 'Nama',
            'tipe_kendaraan' => 'Tipe Kendaraan',
            'no_polisi' => 'No Polisi',
            'berangkat' => 'Tanggal Berangkat',
            'kembali' => 'Tanggal Kembali',
            'instansi' => 'Instansi Tujuan',
            'nama_pejabat' => 'Nama Pejabat',
            'no_telp' => 'No Telepon',
            'tujuan' => 'Tujuan Dinas',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
            'details' => 'Rincian Hasil Dinas Luar',
            'details.*.deskripsi' => 'Rincian Hasil',
        ]);

        $surat = DinasLuar::findOrFail($id);

        $response = $this->allowUpdate($surat);
        if ($response) return $response;

        $surat->update([
            'user_id' => $request->user_id,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'no_polisi' => $request->no_polisi,
            'berangkat' => $request->berangkat,
            'kembali' => $request->kembali,
            'instansi' => $request->instansi,
            'nama_pejabat' => $request->nama_pejabat,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'tujuan' => $request->tujuan,
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
        $existingPJSIds = DetailDinasLuar::where('surat_id', $surat->id)->pluck('id')->toArray();

        // Ambil semua ID pjs yang dikirim dari form (hanya ID yang sudah ada)
        $formDetailsIds = collect($request->details)->pluck('id')->filter()->toArray();

        // Cari data yang harus dihapus (ID di database tapi tidak ada di form)
        $toDelete = array_diff($existingPJSIds, $formDetailsIds);
        DetailDinasLuar::whereIn('id', $toDelete)->delete();

        // Loop data tugas dari form
        foreach ($request->details as $detail) {
            if (isset($detail['id'])) {
                // Jika ID ada di form, update detail lama
                DetailDinasLuar::where('id', $detail['id'])->update([
                    'deskripsi' => $detail['deskripsi'],
                ]);
            } else {
                // Jika ID tidak ada di form, buat detail baru
                DetailDinasLuar::create([
                    'surat_id' => $surat->id,
                    'deskripsi' => $detail['deskripsi'],
                ]);
            }
        }

        return redirect()->route('dinasluar.show', $surat->id)->with('success', "Dinas Luar berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $authUser = Auth::user();
        $surat = DinasLuar::findOrFail($id);
        /*
            - Admin? → silakan hapus, bebas.
            - Bukan admin? → harus pemberi atau pembuat, dan surat belum diterima.
        */
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
        return response()->json(['status' => 'success', 'message' => 'Dinas Luar Berhasil dihapus'], 200);
    }

    public function otoritas(Request $request, string $id)
    {
        $request->validate([
            'target' => ['required'],
            'aksi' => ['required', Rule::in(['otorisasi', 'hapus']),],
        ]);

        $surat = DinasLuar::findOrFail($id);
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

        return response()->json(['status' => 'success', 'message' => 'Otorisasi Form Dinas Keluar Berhasil diupdate', 'data' => $data], 200);
    }

    public function cetak(string $id)
    {
        $surat = DinasLuar::with(['user', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        $user = Auth::user();
        if (is_null($surat->disetujui_at) || ($user->id != $surat->dibuat_id && !$user->hasRole('ADM'))) {
            return redirect()->back()->with('error', 'Anda tidak dapat mencetak surat ini');
        }
        return view('dinasluar.cetak', compact('surat'));
    }
}
