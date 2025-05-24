<?php

namespace App\Http\Controllers;

use App\Models\IjinMasukPabrik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Yajra\DataTables\DataTables;

class IjinMasukPabrikController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view ijin masuk pabrik')->only(['index', 'data', 'show', 'otoritas', 'cetak', 'showKTP']);
        $this->middleware('permission:add ijin masuk pabrik')->only(['create', 'store', 'otoritas']);
        $this->middleware('permission:edit ijin masuk pabrik')->only(['edit', 'update', 'otoritas', 'allowUpdate']);
        $this->middleware('permission:delete ijin masuk pabrik')->only('destroy');
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
        return view('ijinmasukpabrik.index', $data);
    }

    public function data(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);
        $authUser = Auth::user();

        $surat = IjinMasukPabrik::whereBetween('created_at', [$start, $end]);

        if ($authUser->hasRole('ADM')) {
            $surat = $surat->get();
        } else {
            $surat = $surat->where('dibuat_id', $authUser->id)
                ->orWhere('created_by', $authUser->id)
                ->get();
        }

        return DataTables::of($surat)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->buildActionButtons($row, $authUser))
            ->editColumn('masuk', function ($row) {
                $tgl_awal = Carbon::parse($row->masuk)->format('d/m/Y H:i');
                $tgl_akhir = Carbon::parse($row->keluar)->format('d/m/Y H:i');
                return $tgl_awal . ' - ' . $tgl_akhir;
            })
            ->rawColumns(['action', 'masuk'])
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
        $isEditable = is_null($row->disetujui_at);
        $buttons = '';

        $buttons .= '<a href="'. route('ijinpabrik.show', $row->id) .'" class="btn btn-link btn-primary"><i class="fa fa-eye"></i> Show</a>';

        if ($isAdmin || ($authUser->can('edit ijin masuk pabrik') && $isOwnerOrCreator && $isEditable)) {
            $buttons .= '<a href="'. route('ijinpabrik.edit', $row->id) .'" class="btn btn-link btn-warning"><i class="fa fa-edit"></i> Edit</a>';
        }

        if ($isAdmin || ($authUser->can('delete ijin masuk pabrik') && $isOwnerOrCreator && $isEditable)) {
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
        return view('ijinmasukpabrik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string'],
            'masuk' => ['required', 'string'],
            'keluar' => ['nullable', 'string'],
            'keperluan' => ['required', 'string'],
            'nopol' => ['required', 'string'],
            'picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diterima_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'nama' => 'Nama Tamu',
            'masuk' => 'Tanggal Awal',
            'keluar' => 'Tanggal Akhir',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diterima_by' => 'Otorisasi Diterima',
            'disetujui_by' => 'Otorisasi Menyetujui',
        ]);

        $image = $request->file('picture');
        $imageName = time() . '.jpeg';
        $destinationPath = 'surat_masuk_pabrik/' . $imageName;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);

        Storage::disk('local')->put($destinationPath, $image->encodeByExtension('jpeg', 20));

        $no_surat = DB::transaction(function () use ($request) {
            $datePart = Carbon::createFromFormat('d/m/Y H:i', $request->masuk)->format('ym');
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('ijin_masuk_pabriks')
                ->lockForUpdate()
                ->where('no_surat', 'LIKE', "TIM.IP.280.$datePart.%")
                ->latest('id')
                ->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_surat, -5); // Ambil 5 digit terakhir
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return "TIM.IP.280.$datePart.$newNumber";
        });

        $surat = IjinMasukPabrik::create([
            'nama' => $request->nama,
            'no_surat' => $no_surat,
            'masuk' => $request->masuk ? Carbon::createFromFormat('d/m/Y H:i', $request->masuk) : null,
            'keluar' => $request->keluar ? Carbon::createFromFormat('d/m/Y H:i', $request->keluar) : null,
            'keperluan' => $request->keperluan,
            'ktp' => $imageName,
            'nopol' => $request->nopol,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diterima_id' => $request->diterima_by,
            'diterima_at' => $request->diterima_at ? Carbon::parse($request->diterima_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('ijinpabrik.show', $surat->id)->with('success', "Surat Ijin Pabrik $no_surat berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = IjinMasukPabrik::with(['dibuat', 'mengetahui', 'diterima', 'disetujui'])->findOrFail($id);
        return view('ijinmasukpabrik.show', compact('surat'));
    }

    private function allowUpdate($surat)
    {
        $authUser = Auth::user();
        $isNotAdmin = !$authUser->hasRole('ADM');
        $isNotOwnerOrCreator = $authUser->id != $surat->dibuat_id && $authUser->id != $surat->created_by;
        $isAlreadyAcknowledged = !is_null($surat->disetujui_at);
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
        $surat = IjinMasukPabrik::with(['dibuat', 'mengetahui', 'diterima', 'disetujui'])->findOrFail($id);
        $response = $this->allowUpdate($surat);
        if ($response) return $response;
        return view('ijinmasukpabrik.edit', compact('surat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => ['required', 'string'],
            'masuk' => ['nullable', 'string'],
            'keluar' => ['nullable', 'string'],
            'keperluan' => ['required', 'string'],
            'nopol' => ['required', 'string'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'diterima_by' => ['nullable', 'exists:users,id'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
            'exists' => ':attribute tidak terdaftar pada database',
        ], [
            'nama' => 'Nama Tamu',
            'masuk' => 'Tanggal Awal',
            'keluar' => 'Tanggal Akhir',
            'nopol' => 'Nomor Polisi',
            'keperluan' => 'Keperluan',
            'picture' => 'Foto KTP',
            'dibuat_by' => 'Otorisasi Dibuat Oleh',
            'mengetahui_by' => 'Otorisasi Mengetahui',
            'diterima_by' => 'Otorisasi Diterima',
            'disetujui_by' => 'Otorisasi Menyetujui',
        ]);

        $surat = IjinMasukPabrik::findOrFail($id);

        $response = $this->allowUpdate($surat);
        if ($response) return $response;

        $dataUpdate = [
            'nama' => $request->nama,
            'masuk' => $request->masuk ? Carbon::createFromFormat('d/m/Y H:i', $request->masuk) : null,
            'keluar' => $request->keluar ? Carbon::createFromFormat('d/m/Y H:i', $request->keluar) : null,
            'keperluan' => $request->keperluan,
            'nopol' => $request->nopol,
            'ktp' => $surat->ktp,
            'dibuat_id' => $request->dibuat_by,
            'dibuat_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'mengetahui_id' => $request->mengetahui_by,
            'mengetahui_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'diterima_id' => $request->diterima_by,
            'diterima_at' => $request->diterima_at ? Carbon::parse($request->diterima_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'disetujui_id' => $request->disetujui_by,
            'disetujui_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
        ];

        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            $imageName = time() . '.jpeg';
            $destinationPath = 'surat_masuk_pabrik/' . $imageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            if (Storage::disk('local')->exists('surat_masuk_pabrik/' . $surat->ktp)) {
                Storage::disk('local')->delete('surat_masuk_pabrik/' . $surat->ktp);
            }
            Storage::disk('local')->put($destinationPath, $image->encodeByExtension('jpeg', quality: 20));
            $dataUpdate['ktp'] = $imageName;
        }

        $surat->update($dataUpdate);
        $no_surat = $surat->no_surat;

        return redirect()->route('ijinpabrik.show', $surat->id)->with('success', "Surat Ijin Pabrik $no_surat berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $surat = IjinMasukPabrik::findOrFail($id);
        $authUser = Auth::user();
        $isAdmin = $authUser->hasRole('ADM');
        if (!$isAdmin || !$authUser->hasPermissionTo('delete ijin masuk pabrik')) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak dapat menghapus data'], 403);
        }
        if (Storage::disk('local')->exists('surat_masuk_pabrik/' . $surat->ktp)) {
            Storage::disk('local')->delete('surat_masuk_pabrik/' . $surat->ktp);
        }
        $surat->delete();
        return response()->json(['status' => 'success', 'message' => 'Surat Ijin Pabrik Berhasil dihapus'], 200);
    }

    public function otoritas(Request $request, string $id)
    {
        $request->validate([
            'target' => ['required'],
            'aksi' => ['required', Rule::in(['otorisasi', 'hapus']),],
            'foto_kendaraan' => ['nullable', 'image'],
            'foto_sim' => ['nullable', 'image'],
        ]);

        $surat = IjinMasukPabrik::findOrFail($id);
        $field = $request->target;
        $update = [];
        $data = [];

        // Urutan field otorisasi yang harus diperiksa
        $authorizationOrder = [
            ['name' => 'Dibuat Oleh', 'field' => 'dibuat_at'],
            ['name' => 'Disetujui', 'field' => 'disetujui_at'],
            ['name' => 'Diterima', 'field' => 'diterima_at'],
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
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (" . $previousField['name'] . ") belum terisi.", 400);
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

            if ($request->hasFile('foto_kendaraan')) {
                $image = $request->file('foto_kendaraan');
                $imageName = time() . '.jpeg';
                $destinationPath = 'foto_kendaraan/' . $imageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image);
                if (Storage::disk('local')->exists('foto_kendaraan/' . $surat->foto_kendaraan)) {
                    Storage::disk('local')->delete('foto_kendaraan/' . $surat->foto_kendaraan);
                }
                Storage::disk('local')->put($destinationPath, $image->encodeByExtension('jpeg', quality: 20));
                $update['foto_kendaraan'] = $imageName;
            }

            if ($request->hasFile('foto_sim')) {
                $image = $request->file('foto_sim');
                $imageName = time() . '.jpeg';
                $destinationPath = 'foto_sim/' . $imageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image);
                if (Storage::disk('local')->exists('foto_sim/' . $surat->foto_sim)) {
                    Storage::disk('local')->delete('foto_sim/' . $surat->foto_sim);
                }
                Storage::disk('local')->put($destinationPath, $image->encodeByExtension('jpeg', quality: 20));
                $update['foto_sim'] = $imageName;
            }
        } else {
            // Cek apakah field sebelumnya sudah terisi, jika ada field sebelumnya
            if ($fieldIndex < 3) {
                $nextField = $authorizationOrder[$fieldIndex + 1];  // Field sebelumnya dalam urutan
                if (!empty($surat[$nextField['field']])) {
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (" . $nextField['name'] . ") telah terotorisasi.", 400);
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

    public function showKTP(string $id)
    {
        $surat = IjinMasukPabrik::findOrFail($id);
        if (Storage::disk('local')->exists('surat_masuk_pabrik/' . $surat->ktp)) {
            return response()->file(storage_path('app/private/surat_masuk_pabrik/' . $surat->ktp), [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline',
            ]);
        }
        return abort(404);
    }

    public function showKendaraan(string $id)
    {
        $surat = IjinMasukPabrik::findOrFail($id);
        if (Storage::disk('local')->exists('foto_kendaraan/' . $surat->foto_kendaraan)) {
            return response()->file(storage_path('app/private/foto_kendaraan/' . $surat->foto_kendaraan), [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline',
            ]);
        }
        return abort(404);
    }

    public function showSIM(string $id)
    {
        $surat = IjinMasukPabrik::findOrFail($id);
        if (Storage::disk('local')->exists('foto_sim/' . $surat->foto_sim)) {
            return response()->file(storage_path('app/private/foto_sim/' . $surat->foto_sim), [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline',
            ]);
        }
        return abort(404);
    }

    public function cetak(string $id)
    {
        // dd('test');
        $surat = IjinMasukPabrik::with(['dibuat', 'mengetahui', 'diterima', 'disetujui'])
            ->where('id', $id)
            ->firstOrFail();
        $user = Auth::user();
        if (is_null($surat->disetujui_at) || ($user->id != $surat->dibuat_id && !$user->hasRole('ADM'))) {
            return redirect()->back()->with('error', 'Tidak dapat mencetak surat');
        }
        return view('ijinmasukpabrik.cetak', compact('surat'));
    }
}
