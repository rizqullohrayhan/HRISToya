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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getKontrakPengirimanData();
        }

        return view('pengiriman.index');
    }

    private function getKontrakPengirimanData()
    {
        $authUser = Auth::user();
        if ($authUser->hasRole('ADM')) {
            $kontrakPengiriman = MasterKontrakPengiriman::get();
        } else {
            $kontrakPengiriman = MasterKontrakPengiriman::where('created_at', $authUser->id)->orWhere('dibuat_id', $authUser->id)->get();
        }

        return datatables()->of($kontrakPengiriman)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($authUser) {
                return $this->buildActionButtons($row, $authUser);
            })
            ->editColumn('tgl_mulai_kirim', function ($row) {
                return Carbon::parse($row->tgl_mulai_kirim)->format('d/m/Y');
            })
            ->editColumn('batas_kirim', function ($row) {
                return Carbon::parse($row->batas_kirim)->format('d/m/Y');
            })
            ->rawColumns(['action', 'tgl_mulai_kirim', 'batas_kirim'])
            ->make(true);
    }

    private function buildActionButtons($row, $authUser)
    {
        $btn = '
            <a href="' . route('kontrak.show', $row->id) . '" title="Show" class="btn btn-link btn-primary">
                <i class="fa fa-eye"></i>&nbsp;Show
            </a>
        ';

        if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('edit kontrak pengiriman')) || $authUser->hasRole('ADM')) {
            $btn .= '
                <a href="' . route('kontrak.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning">
                    <i class="fa fa-edit"></i>&nbsp;Edit
                </a>
            ';
        }

        if (($authUser->id == $row->user_id && $authUser->hasPermissionTo('delete kontrak pengiriman')) || $authUser->hasRole('ADM')) {
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
        $request->validate([
            'perusahaan' => ['required', 'string', 'max:255'],
            'customer' => ['required', 'string', 'max:255'],
            'no_kontrak' => ['required', 'string', 'max:255'],
            'barang' => ['required', 'string', 'max:255'],
            'kuantitas' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'string', 'max:255'],
            'tgl_mulai_kirim' => ['required', 'date_format:d/m/Y'],
            'jangka_waktu_kirim' => ['required', 'string'],
            'batas_kirim' => ['required', 'date_format:d/m/Y'],
            'kebun' => ['required', 'array', 'min:1'],
            'kebun.*.vendor' => ['nullable', 'string', 'min:255'],
            'kebun.*.kebun' => ['required', 'string', 'max:255'],
            'kebun.*.kontrak' => ['required', 'integer'],
        ]);

        $tgl_mulai_kirim = Carbon::createFromFormat('d/m/Y', $request->tgl_mulai_kirim)->format('Y-m-d');
        $batas_kirim = Carbon::createFromFormat('d/m/Y', $request->batas_kirim)->format('Y-m-d');
        $request->merge([
            'tgl_mulai_kirim' => $tgl_mulai_kirim,
            'batas_kirim' => $batas_kirim,
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
            'tgl_mulai_kirim' => ['required', 'date_format:d/m/Y'],
            'jangka_waktu_kirim' => ['required', 'string'],
            'batas_kirim' => ['required', 'date_format:d/m/Y'],
            'kebun' => ['required', 'array', 'min:1'],
            'kebun.*.vendor' => ['nullable', 'string', 'min:255'],
            'kebun.*.kebun' => ['required', 'string', 'max:255'],
            'kebun.*.kontrak' => ['required', 'integer'],
        ]);

        $tgl_mulai_kirim = Carbon::createFromFormat('d/m/Y', $request->tgl_mulai_kirim)->format('Y-m-d');
        $batas_kirim = Carbon::createFromFormat('d/m/Y', $request->batas_kirim)->format('Y-m-d');
        $request->merge([
            'tgl_mulai_kirim' => $tgl_mulai_kirim,
            'batas_kirim' => $batas_kirim,
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
