<?php

namespace App\Http\Controllers\Pengiriman;

use App\Http\Controllers\Controller;
use App\Models\DetailRealisasiPengiriman;
use App\Models\RekapKebunPengiriman;
use App\Traits\KontrakPengirimanTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailRealisasiController extends Controller
{
    use KontrakPengirimanTrait;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view detail realisasi pengiriman')->only(['index', 'getDetailRealisasiData', 'show', 'buildActionButtons']);
        $this->middleware('permission:add detail realisasi pengiriman')->only(['create', 'store']);
        $this->middleware('permission:edit detail realisasi pengiriman')->only(['edit', 'inlineEdit', 'update']);
        $this->middleware('permission:delete detail realisasi pengiriman')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->kontrak_id;
            return $this->getDetailRealisasiData($id);
        }
    }

    private function getDetailRealisasiData($id)
    {
        $detailRealisasi = DetailRealisasiPengiriman::query()
        ->join('rekap_kebun_pengiriman', 'rekap_kebun_pengiriman.id', '=', 'detail_realisasi_pengiriman.rekap_kebun_pengiriman_id')
        ->where('rekap_kebun_pengiriman.master_kontrak_pengiriman_id', $id)
        ->select('detail_realisasi_pengiriman.*', 'rekap_kebun_pengiriman.kebun')
        ->get();

        return datatables()->of($detailRealisasi)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($id) {
                return $this->buildActionButtons($row, $id);
            })
            ->addColumn('kebun_span', function ($row) {
                $value = $row->kebun ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable-select" data-id="'.$row->id.'" data-name="rekap_kebun_pengiriman_id" data-value="'.($row->rekap_kebun_pengiriman_id ?? '').'">'.$value.'</span>';
            })
            ->addColumn('tgl_span', function ($row) {
                $tgl = $row->tgl ? Carbon::parse($row->tgl)->format('d/m/Y') : '&nbsp;&nbsp;&nbsp;&nbsp;';
                $value = $tgl == '' ? '' : $tgl;
                return '<span class="editable-date" data-id="'.$row->id.'" data-name="tgl" data-value="'.$value.'">'.$tgl.'</span>';
            })
            ->addColumn('nopol_span', function ($row) {
                $value = $row->nopol ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="nopol" data-value="'.($row->nopol ?? '').'">'.$value.'</span>';
            })
            ->addColumn('no_sj_span', function ($row) {
                $value = $row->no_sj ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="no_sj" data-value="'.($row->no_sj ?? '').'">'.$value.'</span>';
            })
            ->addColumn('no_so_pkt_span', function ($row) {
                $value = $row->no_so_pkt ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="no_so_pkt" data-value="'.($row->no_so_pkt ?? '').'">'.$value.'</span>';
            })
            ->addColumn('vendor_span', function ($row) {
                $value = $row->vendor ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="vendor" data-value="'.($row->vendor ?? '').'">'.$value.'</span>';
            })
            ->addColumn('kirim_span', function ($row) {
                $value = $row->kirim !== null ? $row->kirim : '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable format-number" data-id="'.$row->id.'" data-name="kirim" data-value="'.($row->kirim ?? 0).'">'.$value.'</span>';
            })
            ->addColumn('terima_span', function ($row) {
                $value = $row->terima !== null ? $row->terima : '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable format-number" data-id="'.$row->id.'" data-name="terima" data-value="'.($row->terima ?? 0).'">'.$value.'</span>';
            })
            ->rawColumns(['action', 'kebun_span', 'tgl_span', 'nopol_span', 'no_sj_span', 'no_so_pkt_span', 'vendor_span', 'kirim_span', 'terima_span'])
            ->make(true);
    }

    private function buildActionButtons($row, $kontrakId)
    {
        $authUser = Auth::user();

        $btn = '';

        // if ($authUser->hasRole('ADM') || $authUser->hasPermissionTo('edit detail realisasi pengiriman')) {
        //     $btn .= '
        //         <a href="' . route('detail_realisasi.edit', $row->id) . '?kontrak='. $kontrakId .'" title="Edit" class="btn btn-link btn-warning">
        //             <i class="fa fa-edit"></i>&nbsp;Edit
        //         </a>
        //     ';
        // }

        if ($authUser->hasRole('ADM') || $authUser->can('delete detail realisasi pengiriman')) {
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
    public function create(Request $request)
    {
        $rekapKebun = RekapKebunPengiriman::where('master_kontrak_pengiriman_id', $request->kontrak)->get();

        $data = [
            'kontrakId' => $request->kontrak,
            'rekapKebun' => $rekapKebun,
        ];
        return view('pengiriman.detail.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rekap_kebun_pengiriman_id' => ['required', 'exists:rekap_kebun_pengiriman,id'],
            'tgl' => ['required'],
            'nopol' => ['nullable', 'string'],
            'no_sj' => ['nullable', 'string'],
            'no_so_pkt' => ['nullable', 'string'],
            'vendor' => ['nullable', 'string'],
            'kirim' => ['nullable', 'string'],
            'terima' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'exists' => ':attribute tidak terdaftar pada database',
            'string' => ':attribute harus berupa string',
            'integer' => ':attribute harus berupa angka',
        ], [
            'rekap_kebun_pengiriman_id' => 'Kebun',
            'tgl' => 'Tanggal',
            'nopol' => 'Nopol',
            'no_sj' => 'No SJ',
            'no_so_pkt' => 'No SO PKT',
            'vendor' => 'Vendor',
            'kirim' => 'Kirim',
            'terima' => 'Terima',
        ]);

        DetailRealisasiPengiriman::create([
            'rekap_kebun_pengiriman_id' => $request->rekap_kebun_pengiriman_id,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'nopol' => $request->nopol,
            'no_sj' => $request->no_sj,
            'no_so_pkt' => $request->no_so_pkt,
            'vendor' => $request->vendor,
            'kirim' => $request->kirim,
            'terima' => $request->terima,
        ]);

        $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Realisasi berhasil ditambahkan',
            'rekap_html' => view('pengiriman._rekap_data', $rekapData)->render(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $detail = DetailRealisasiPengiriman::findOrFail($id);
        $kontrak = $request->kontrak;
        $rekapKebun = RekapKebunPengiriman::where('master_kontrak_pengiriman_id', $kontrak)->get();

        $detail->tgl = $detail->tgl ? Carbon::parse($detail->tgl)->format('d/m/Y') : '';

        $data = [
            'rekapKebun' => $rekapKebun,
            'detail' => $detail,
            'kontrakId' => $kontrak,
        ];
        return view('pengiriman.detail.edit', $data);
    }

    public function inlineEdit(Request $request, string $id)
    {
        $dataSO = DetailRealisasiPengiriman::findOrFail($id);
        $field = $request->input('name', $request->keys()[0]);
        $value = $request->input($field);

        if ($field === 'tgl') {
            $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

        $dataSO->$field = $value;
        $dataSO->save();

        $updateRekapData = false;
        $rekap_html = '';

        if (in_array($field, ['tgl', 'rekap_kebun_pengiriman_id', 'kirim', 'terima'])) {
            $updateRekapData = true;
            $rekapData = $this->generateRekapData($request->kontrakId);
            $rekap_html = view('pengiriman._rekap_data', $rekapData)->render();
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Detail Realisasi Pengiriman berhasil diupdate',
            'updateRekapData' => $updateRekapData,
            'rekap_html' => $rekap_html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'rekap_kebun_pengiriman_id' => ['required', 'exists:rekap_kebun_pengiriman,id'],
            'tgl' => ['required'],
            'nopol' => ['nullable', 'string'],
            'no_sj' => ['nullable', 'string'],
            'no_so_pkt' => ['nullable', 'string'],
            'vendor' => ['nullable', 'string'],
            'kirim' => ['nullable', 'string'],
            'terima' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'exists' => ':attribute tidak terdaftar pada database',
            'string' => ':attribute harus berupa string',
            'integer' => ':attribute harus berupa angka',
        ], [
            'rekap_kebun_pengiriman_id' => 'Kebun',
            'tgl' => 'Tanggal',
            'nopol' => 'Nopol',
            'no_sj' => 'No SJ',
            'no_so_pkt' => 'No SO PKT',
            'vendor' => 'Vendor',
            'kirim' => 'Kirim',
            'terima' => 'Terima',
        ]);

        $rencana = DetailRealisasiPengiriman::findOrFail($id);
        $rencana->update([
            'rekap_kebun_pengiriman_id' => $request->rekap_kebun_pengiriman_id,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'nopol' => $request->nopol,
            'no_sj' => $request->no_sj,
            'no_so_pkt' => $request->no_so_pkt,
            'vendor' => $request->vendor,
            'kirim' => $request->kirim,
            'terima' => $request->terima,
        ]);

        $kontrak = $request->kontrakId;

        return redirect()->route('kontrak_pengiriman.show', $kontrak)->with('success', 'Detail realisasi berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $detail = DetailRealisasiPengiriman::findOrFail($id);
        $detail->delete();

        $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Realisasi Pengiriman Berhasil dihapus',
            'rekap_html' => view('pengiriman._rekap_data', $rekapData)->render(),
        ]);
    }
}
