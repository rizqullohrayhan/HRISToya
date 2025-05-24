<?php

namespace App\Http\Controllers\Pengiriman;

use App\Http\Controllers\Controller;
use App\Models\MasterKontrakPengiriman;
use App\Models\RekapKebunPengiriman;
use App\Traits\KontrakPengirimanTrait;
use Carbon\Carbon;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontrakPengirimanController extends Controller
{
    use KontrakPengirimanTrait;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view kontrak pengiriman')->only(['index', 'getKontrakPengirimanData', 'show', 'buildActionButtons', 'cetak']);
        $this->middleware('permission:add kontrak pengiriman')->only(['create', 'store']);
        $this->middleware('permission:edit kontrak pengiriman')->only(['edit', 'update']);
        $this->middleware('permission:delete kontrak pengiriman')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getKontrakPengirimanData($request);
        }

        $data = [
            'start' => Carbon::now()->startOfMonth()->format('d/m/Y'),
            'end' => Carbon::now()->endOfMonth()->format('d/m/Y'),
        ];
        return view('pengiriman.index', $data);
    }

    private function getKontrakPengirimanData(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);
        $authUser = Auth::user();

        $kontrakPengiriman = MasterKontrakPengiriman::whereBetween('tgl_mulai_kirim', [$start, $end]);

        if ($authUser->hasRole('ADM')) {
            $kontrakPengiriman = $kontrakPengiriman->get();
        } else {
            $kontrakPengiriman = $kontrakPengiriman->where(function ($query) use ($authUser) {
                $query->where('created_by', $authUser->id)->orWhere('dibuat_id', $authUser->id);
            })->get();
        }

        return datatables()->of($kontrakPengiriman)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->buildActionButtons($row, $authUser))
            ->editColumn('tgl_mulai_kirim', fn($row) => Carbon::parse($row->tgl_mulai_kirim)->format('d/m/Y'))
            ->editColumn('batas_kirim', fn($row) => Carbon::parse($row->batas_kirim)->format('d/m/Y'))
            ->rawColumns(['action', 'tgl_mulai_kirim', 'batas_kirim'])
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

    private function buildActionButtons($row, $authUser)
    {
        $btn = '
            <a href="' . route('kontrak.show', $row->id) . '" title="Show" class="btn btn-link btn-primary">
                <i class="fa fa-eye"></i>&nbsp;Show
            </a>
        ';

        if (($authUser->id == $row->user_id && $authUser->can('edit kontrak pengiriman')) || $authUser->hasRole('ADM')) {
            $btn .= '
                <a href="' . route('kontrak.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning">
                    <i class="fa fa-edit"></i>&nbsp;Edit
                </a>
            ';
        }

        if (($authUser->id == $row->user_id && $authUser->can('delete kontrak pengiriman')) || $authUser->hasRole('ADM')) {
            $btn .= '
                <button type="button" data-id="' . $row->id . '" title="Hapus Kontrak" class="btn btn-link btn-danger btn-destroy">
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
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengiriman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->merge([
        //     'tgl_mulai_kirim' => $request->tgl_mulai_kirim ? Carbon::createFromFormat('d/m/Y', $request->tgl_mulai_kirim)->format('Y-m-d') : null,
        //     'batas_kirim' => $request->batas_kirim ? Carbon::createFromFormat('d/m/Y', $request->batas_kirim)->format('Y-m-d') : null,
        // ]);
        $request->validate([
            'perusahaan' => ['required', 'string', 'max:255'],
            'customer' => ['required', 'string', 'max:255'],
            'no_kontrak' => ['required', 'string', 'max:255'],
            'barang' => ['required', 'string', 'max:255'],
            'kuantitas' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'string', 'max:255'],
            'tahun' => ['nullable', 'integer'],
            'tgl_mulai_kirim' => ['required', 'date'],
            'jangka_waktu_kirim' => ['required', 'string'],
            'batas_kirim' => ['required', 'date', 'after_or_equal:tgl_mulai_kirim'],
            'kebun' => ['required', 'array', 'min:1'],
            'kebun.*.vendor' => ['nullable', 'string', 'min:255'],
            'kebun.*.kebun' => ['required', 'string', 'max:255'],
            'kebun.*.kontrak' => ['required', 'integer'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute tidak boleh lebih dari :max karakter',
            'integer' => ':attribute harus berupa angka',
            'date' => ':attribute harus berupa tanggal yang valid',
            'after_or_equal' => ':attribute harus setelah atau sama dengan :date',
        ], [
            'perusahaan' => 'Perusahaan',
            'customer' => 'Customer',
            'no_kontrak' => 'No Kontrak',
            'barang' => 'Barang',
            'kuantitas' => 'Kuantitas',
            'semester' => 'Semester',
            'tahun' => 'Tahun',
            'tgl_mulai_kirim' => 'Tanggal Mulai Kirim',
            'jangka_waktu_kirim' => 'Jangka Waktu Kirim',
            'batas_kirim' => 'Batas Kirim',
            'kebun' => 'Kebun',
        ]);

        $kebunList = collect($request->kebun)->pluck('kebun');
        $duplicates = $kebunList->duplicates();

        if ($duplicates->isNotEmpty()) {
            $duplicateNames = $duplicates->implode(', ');
            return back()->withErrors([
                'kebun' => "Terdapat data kebun yang duplikat: $duplicateNames."
            ])->withInput();
        }
        $request->merge([
            'created_by' => Auth::user()->id,
        ]);

        $masterKontrak = MasterKontrakPengiriman::create($request->except('kebun', '_token'));

        $masterKontrak->rekapKebunPengiriman()->createMany($request->kebun);

        return redirect()->route('kontrak_pengiriman.show', $masterKontrak->id)->with('success', 'Kontrak Pengiriman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->generateRekapData($id);

        return view('pengiriman.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kontrak = MasterKontrakPengiriman::with('rekapKebunPengiriman')->findOrFail($id);
        return view('pengiriman.edit', compact('kontrak'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'perusahaan' => ['required', 'string', 'max:255'],
            'customer' => ['required', 'string', 'max:255'],
            'no_kontrak' => ['required', 'string', 'max:255'],
            'barang' => ['required', 'string', 'max:255'],
            'kuantitas' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'string', 'max:255'],
            'tahun' => ['nullable', 'integer'],
            'tgl_mulai_kirim' => ['required', 'date'],
            'jangka_waktu_kirim' => ['required', 'string'],
            'batas_kirim' => ['required', 'date', 'after_or_equal:tgl_mulai_kirim'],
            'kebun' => ['required', 'array', 'min:1'],
            'kebun.*.vendor' => ['nullable', 'string', 'min:255'],
            'kebun.*.kebun' => ['required', 'string', 'max:255'],
            'kebun.*.kontrak' => ['required', 'integer'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute tidak boleh lebih dari :max karakter',
            'integer' => ':attribute harus berupa angka',
            'date' => ':attribute harus berupa tanggal yang valid',
            'after_or_equal' => ':attribute harus setelah atau sama dengan :date',
        ], [
            'perusahaan' => 'Perusahaan',
            'customer' => 'Customer',
            'no_kontrak' => 'No Kontrak',
            'barang' => 'Barang',
            'kuantitas' => 'Kuantitas',
            'semester' => 'Semester',
            'tahun' => 'Tahun',
            'tgl_mulai_kirim' => 'Tanggal Mulai Kirim',
            'jangka_waktu_kirim' => 'Jangka Waktu Kirim',
            'batas_kirim' => 'Batas Kirim',
            'kebun' => 'Kebun',
        ]);

        $masterKontrak = MasterKontrakPengiriman::firstOrFail('id', $request->id);
        $masterKontrak->update($request->except('kebun', '_token', '_method'));

        // Ambil semua ID Kebun yang ada di database
        $existingKebunIds = RekapKebunPengiriman::where('master_kontrak_pengiriman_id', $masterKontrak->id)->pluck('id')->toArray();

        // Ambil semua ID Kebun yang dikirim dari form (hanya ID yang sudah ada)
        $formKebunIds = collect($request->kebun)->pluck('id')->filter()->toArray();

        // Cari data yang harus dihapus (ID di database tapi tidak ada di form)
        $toDelete = array_diff($existingKebunIds, $formKebunIds);
        RekapKebunPengiriman::whereIn('id', $toDelete)->delete();

        // Loop data tujuan dari form
        foreach ($request->kebun as $kebun) {
            if (isset($kebun['id'])) {
                // Jika ID ada di form, update detail lama
                RekapKebunPengiriman::where('id', $kebun['id'])->update([
                    'vendor' => $kebun['vendor'],
                    'kebun' => $kebun['kebun'],
                    'kontrak' => $kebun['kontrak'],
                ]);
            } else {
                // Jika ID tidak ada di form, buat detail baru
                RekapKebunPengiriman::create([
                    'master_kontrak_pengiriman_id' => $masterKontrak->id,
                    'vendor' => $kebun['vendor'],
                    'kebun' => $kebun['kebun'],
                    'kontrak' => $kebun['kontrak'],
                ]);
            }
        }

        return redirect()->route('kontrak_pengiriman.show', $masterKontrak->id)->with('success', 'Kontrak Pengiriman berhasil dibuat.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterKontrakPengiriman $kontrak)
    {
        $kontrak->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Kontrak Berhasil dihapus',
        ], 200);
    }

    public function cetak(string $id)
    {
        $data = $this->generateRekapData($id);

        return view('pengiriman.print.rekap_data', $data);
    }
}
