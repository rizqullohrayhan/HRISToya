<?php

namespace App\Http\Controllers;

use App\Models\DinasLuarKota;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DinasLuarKotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dinasluarkota.index');
    }

    public function data(Request $request)
    {
        $tahun = $request->tahun ? $request->tahun : date('Y');
        $authUser = Auth::user();
        if ($authUser->hasRole('ADM')) {
            $surat = DinasLuarKota::with(['penerima', 'pemberi'])
                ->whereYear('berangkat', $tahun)
                ->get();
        } else {
            $teamId = $authUser->team_id;
            $surat = DinasLuarKota::with(['penerima', 'pemberi'])
                ->whereYear('berangkat', $tahun)
                ->where(function ($query) use ($teamId, $authUser) {
                    $query->whereHas('penerima', function ($q) use ($teamId) {
                        $q->where('team_id', $teamId);
                    })->orWhere('pemberi_id', $authUser->id);
                })
                ->get();
        }

        return datatables()::of($surat)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($authUser) {
                $isAdmin = $authUser->hasRole('ADM');
                $isOwnerOrCreator = $authUser->id == $row->dibuat_id || $authUser->id == $row->created_by;
                $isEditable = is_null($row->diperiksa_at);
                $btn = '
                            <a href="' . route('dinasluarkota.show', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('edit dinas luar kota') && $isOwnerOrCreator && $isEditable)
                ) {
                    $btn .= '
                                <a href="' . route('dinasluarkota.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>&nbsp;Edit
                                </a>
                            ';
                }
                if (
                    $isAdmin ||
                    ($authUser->hasPermissionTo('delete dinas luar kota') && $isOwnerOrCreator && $isEditable)
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
            ->addColumn('berangkat', function ($row) {
                $berangkat = Carbon::parse($row->berangkat)->format('d/m/Y');
                return $berangkat;
            })
            ->rawColumns(['action', 'berangkat'])
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
        return view('dinasluarkota.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'pemberi_id' => ['required', 'exists:users,id'],
            'kendaraan' => ['required', 'string'],
            'no_polisi' => ['required', 'string'],
            'kota' => ['required', 'string'],
            'jangka_waktu' => ['required', 'string'],
            'satuan_waktu' => ['required', 'string'],
            'berangkat' => ['required', 'date_format:d/m/Y'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.instansi' => ['nullable', 'string'],
            'details.*.menemui' => ['nullable', 'string'],
            'details.*.tujuan' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'penerima_id' => 'Penerima Tugas',
            'pemberi_id' => 'Pemberi Tugas',
            'kendaraan' => 'Tipe Kendaraan',
            'no_polisi' => 'No Polisi',
            'kota' => 'Kota Tujuan',
            'jangka_waktu' => 'Kota Tujuan',
            'berangkat' => 'Tanggal Berangkat',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
            'details' => 'Rincian Hasil Dinas Luar Kota',
            'details.*.instansi' => 'Instansi',
            'details.*.menemui' => 'Pejabat Ditemui',
            'details.*.tujuan' => 'Tujuan',
        ]);

        $no_surat = DB::transaction(function () use ($request) {
            $datePart = Carbon::createFromFormat('d/m/Y', $request->berangkat)->format('ym'); // contoh: 2504
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('dinas_luar_kotas')
                            ->lockForUpdate()
                            ->where('no_surat', 'LIKE', "TIM.LK.280.$datePart.%")
                            ->latest('id')
                            ->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_surat, -5); // Ambil 5 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return "TIM.LK.280.$datePart.$newNumber";
        });

        $surat = DinasLuarKota::create([
            'no_surat' => $no_surat,
            'penerima_id' => $request->penerima_id,
            'pemberi_id' => $request->pemberi_id,
            'kendaraan' => $request->kendaraan,
            'no_polisi' => $request->no_polisi,
            'kota' => $request->kota,
            'jangka_waktu' => $request->jangka_waktu,
            'satuan_waktu' => $request->satuan_waktu,
            'berangkat' => $request->berangkat ? Carbon::createFromFormat('d/m/Y', $request->berangkat)->format('Y-m-d H:i:s') : null,
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

        $surat->detail()->createMany($request->details);

        return redirect()->route('dinasluarkota.show', $surat->id)->with('success', "Dinas Luar Kota $no_surat berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = DinasLuarKota::with(['penerima', 'pemberi', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        return view('dinasluarkota.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = DinasLuarKota::with(['penerima', 'pemberi', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        $response = $this->allowUpdate($surat);
        if ($response) return $response;
        $user = User::get();
        $data = [
            'surat' => $surat,
            'users' => $user,
        ];
        return view('dinasluarkota.edit', $data);
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
        $request->validate([
            'penerima_id' => ['required', 'exists:users,id'],
            'pemberi_id' => ['required', 'exists:users,id'],
            'kendaraan' => ['required', 'string'],
            'no_polisi' => ['required', 'string'],
            'kota' => ['required', 'string'],
            'jangka_waktu' => ['required', 'string'],
            'satuan_waktu' => ['required', 'string'],
            'berangkat' => ['required', 'date_format:d/m/Y'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diperiksa_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'details' => ['required', 'array', 'min:1'],
            'details.*.instansi' => ['nullable', 'string'],
            'details.*.menemui' => ['nullable', 'string'],
            'details.*.tujuan' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'penerima_id' => 'Penerima Tugas',
            'pemberi_id' => 'Pemberi Tugas',
            'kendaraan' => 'Tipe Kendaraan',
            'no_polisi' => 'No Polisi',
            'kota' => 'Kota Tujuan',
            'jangka_waktu' => 'Kota Tujuan',
            'berangkat' => 'Tanggal Berangkat',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diperiksa_by' => 'Otorisasi Diperiksa',
            'disetujui_by' => 'Otorisasi Menyetujui',
            'details' => 'Rincian Hasil Dinas Luar Kota',
            'details.*.instansi' => 'Instansi',
            'details.*.menemui' => 'Pejabat Ditemui',
            'details.*.tujuan' => 'Tujuan',
        ]);

        $surat = DinasLuarKota::findOrFail($id);

        $response = $this->allowUpdate($surat);
        if ($response) return $response;

        $surat->update([
            'penerima_id' => $request->penerima_id,
            'pemberi_id' => $request->pemberi_id,
            'kendaraan' => $request->kendaraan,
            'no_polisi' => $request->no_polisi,
            'kota' => $request->kota,
            'jangka_waktu' => $request->jangka_waktu,
            'satuan_waktu' => $request->satuan_waktu,
            'berangkat' => $request->berangkat ? Carbon::createFromFormat('d/m/Y', $request->berangkat)->format('Y-m-d H:i:s') : null,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diperiksa_id' => $request->diperiksa_by,
            'diperiksa_at' => $request->diperiksa_at ? Carbon::parse($request->diperiksa_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
        ]);

        $surat->detail()->delete();
        $surat->detail()->createMany($request->details);

        return redirect()->route('dinasluarkota.show', $surat->id)->with('success', "Dinas Luar Kota berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $authUser = Auth::user();
        $surat = DinasLuarKota::findOrFail($id);
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
        return response()->json(['status' => 'success', 'message' => 'Dinas Luar Kota Berhasil dihapus'], 200);
    }

    public function otoritas(Request $request, string $id)
    {
        $request->validate([
            'target' => ['required'],
            'aksi' => ['required', Rule::in(['otorisasi', 'hapus']),],
        ]);

        $surat = DinasLuarKota::findOrFail($id);
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

        return response()->json(['status' => 'success', 'message' => 'Otorisasi Form Dinas Luar Kota Berhasil diupdate'], 200);
    }

    public function cetak(string $id)
    {
        $surat = DinasLuarKota::with(['penerima', 'pemberi', 'dibuat', 'mengetahui', 'diperiksa', 'disetujui', 'detail'])
                            ->where('id', $id)
                            ->firstOrFail();
        $user = Auth::user();
        if (is_null($surat->disetujui_at) || ($user->id != $surat->dibuat_id && !$user->hasRole('ADM'))) {
            return redirect()->back()->with('error', 'Anda tidak dapat mencetak surat ini');
        }
        return view('dinasluarkota.cetak', compact('surat'));
    }
}
