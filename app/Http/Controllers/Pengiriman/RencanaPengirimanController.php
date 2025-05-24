<?php

namespace App\Http\Controllers\Pengiriman;

use App\Http\Controllers\Controller;
use App\Models\MasterKontrakPengiriman;
use App\Models\RekapKebunPengiriman;
use App\Models\RencanaPengiriman;
use App\Traits\KontrakPengirimanTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RencanaPengirimanController extends Controller
{
    use KontrakPengirimanTrait;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view rencana pengiriman')->only(['index', 'getRencanaPengirimanData', 'show', 'buildActionButtons', 'cetak']);
        $this->middleware('permission:add rencana pengiriman')->only(['create', 'store']);
        $this->middleware('permission:edit rencana pengiriman')->only(['edit', 'inlineEdit', 'update']);
        $this->middleware('permission:delete rencana pengiriman')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->kontrak_id;
            return $this->getRencanaPengirimanData($id);
        }
    }

    private function getRencanaPengirimanData($id)
    {
        $rencanaPengiriman = RencanaPengiriman::query()
            ->join('rekap_kebun_pengiriman', 'rekap_kebun_pengiriman.id', '=', 'rencana_pengirimen.rekap_kebun_pengiriman_id')
            ->where('rekap_kebun_pengiriman.master_kontrak_pengiriman_id', $id)
            ->select('rencana_pengirimen.*', 'rekap_kebun_pengiriman.id AS kebun_id', 'rekap_kebun_pengiriman.vendor', 'rekap_kebun_pengiriman.kebun')
            ->get();

        return datatables()->of($rencanaPengiriman)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return $this->buildActionButtons($row);
            })
            ->addColumn('kebun_raw', fn($row) => $row->kebun)
            ->addColumn('tgl_raw', fn($row) => $row->tgl)
            ->addColumn('nopol_raw', fn($row) => $row->nopol)
            ->editColumn('kebun', function ($row) {
                $value = $row->kebun ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable-select" data-id="'.$row->id.'" data-name="rekap_kebun_pengiriman_id" data-value="'.($row->rekap_kebun_pengiriman_id ?? '').'">'.$value.'</span>';
            })
            ->editColumn('tgl', function ($row) {
                $tgl = $row->tgl ? Carbon::parse($row->tgl)->format('d/m/Y') : '&nbsp;&nbsp;&nbsp;&nbsp;';
                $value = $tgl == '' ? '' : $tgl;
                return '<span class="editable-date" data-id="'.$row->id.'" data-name="tgl" data-value="'.$value.'">'.$tgl.'</span>';
            })
            ->editColumn('nopol', function ($row) {
                $value = $row->nopol ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="nopol" data-value="'.($row->nopol).'">'.$value.'</span>';
            })
            ->editColumn('qty', function ($row) {
                $value = $row->qty !== null ? $row->qty : '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable format-number" data-id="'.$row->id.'" data-name="qty" data-value="'.($row->qty ?? 0).'">'.$value.'</span>';
            })
            ->rawColumns(['kebun', 'tgl', 'nopol', 'qty', 'action'])
            ->make(true);
    }

    private function buildActionButtons($row)
    {
        $authUser = Auth::user();

        $btn = '';

        if ($authUser->hasRole('ADM') || $authUser->can('delete rencana pengiriman')) {
            $btn .= '
                <button type="button" data-id="' . $row->id . '" title="Hapus Rencana" class="btn btn-link btn-danger btn-destroy">
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
        $rekapKebun = RekapKebunPengiriman::where('master_kontrak_pengiriman_id', $request->kontrakId)->get();

        $data = [
            'title' => 'Tambah Rencana Pengiriman',
            'action' => route('rencana_pengiriman.store'),
            'rekapKebun' => $rekapKebun
        ];
        return view('pengiriman.rencana.form-create', $data);
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
            'qty' => ['required', 'integer']
        ], [
            'required' => ':attribute wajib diisi',
            'exists' => ':attribute tidak terdaftar pada database',
            'string' => ':attribute harus berupa string',
            'integer' => ':attribute harus berupa angka',
        ], [
            'rekap_kebun_pengiriman_id' => 'Kebun',
            'tgl' => 'tanggal',
        ]);

        RencanaPengiriman::create([
            'rekap_kebun_pengiriman_id' => $request->rekap_kebun_pengiriman_id,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'nopol' => $request->nopol,
            'qty' => $request->qty,
        ]);

        $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Rencana pengiriman berhasil ditambahkan',
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
        $rencana = RencanaPengiriman::findOrFail($id);
        $rekapKebun = RekapKebunPengiriman::where('master_kontrak_pengiriman_id', $request->kontrakId)->get();

        $rencana->tgl = Carbon::parse($rencana->tgl)->format('d/m/Y');

        $data = [
            'title' => 'Edit Rencana Pengiriman',
            'action' => route('rencana_pengiriman.update', $id),
            'rencana' => $rencana,
            'rekapKebun' => $rekapKebun,
        ];
        return view('pengiriman.rencana.form-edit', $data);
    }

    public function inlineEdit(Request $request, string $id)
    {
        $rencana = RencanaPengiriman::findOrFail($id);
        $field = $request->input('name', $request->keys()[0]);
        $value = $request->input($field);

        if ($field === 'tgl') {
            $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

        if ($field === 'rekap_kebun_pengiriman_id') {
            $request->validate([
                $field => 'required|exists:rekap_kebun_pengiriman,id'
            ]);
        }

        $rencana->$field = $value;
        $rencana->save();

        $updateRekapData = false;
        $rekap_html = '';

        if ($field === 'tgl' || $field === 'rekap_kebun_pengiriman_id') {
            $updateRekapData = true;
            $rekapData = $this->generateRekapData($request->kontrakId);
            $rekap_html = view('pengiriman._rekap_data', $rekapData)->render();
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Rencana pengiriman berhasil diupdate',
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
            'qty' => ['required', 'integer']
        ], [
            'required' => ':attribute wajib diisi',
            'exists' => ':attribute tidak terdaftar pada database',
            'string' => ':attribute harus berupa string',
            'integer' => ':attribute harus berupa angka',
        ], [
            'rekap_kebun_pengiriman_id' => 'Kebun',
            'tgl' => 'tanggal',
        ]);

        $rencana = RencanaPengiriman::findOrFail($id);
        $rencana->update([
            'rekap_kebun_pengiriman_id' => $request->rekap_kebun_pengiriman_id,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'nopol' => $request->nopol,
            'qty' => $request->qty,
        ]);

        $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Rencana pengiriman berhasil diupdate',
            'rekap_html' => view('pengiriman._rekap_data', $rekapData)->render(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // $authUser = Auth::user();
        $surat = RencanaPengiriman::findOrFail($id);
        $surat->delete();

        $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Rencana Pengiriman Berhasil dihapus',
            'rekap_html' => view('pengiriman._rekap_data', $rekapData)->render(),
        ], 200);
    }

    public function cetak(string $id)
    {
        // $kontrakPengiriman = MasterKontrakPengiriman::findOrFail($id);
        // $rekapKebun = DB::table('rekap_kebun_pengiriman')
        //     ->leftJoin('rencana_pengirimen', 'rencana_pengirimen.rekap_kebun_pengiriman_id', '=', 'rekap_kebun_pengiriman.id')
        //     ->where('rekap_kebun_pengiriman.master_kontrak_pengiriman_id', $id)
        //     ->select(
        //         'rekap_kebun_pengiriman.id AS rekap_id',
        //         'rekap_kebun_pengiriman.kontrak',
        //         'rekap_kebun_pengiriman.vendor',
        //         'rekap_kebun_pengiriman.kebun',
        //         'rencana_pengirimen.tgl',
        //         'rencana_pengirimen.qty',
        //         'rencana_pengirimen.nopol'
        //     )
        //     ->get();

        // $tglPengiriman = $rekapKebun->pluck('tgl')->whereNotNull()->unique()->sort()->values();
        // $tglChunks = $tglPengiriman->chunk(4);

        // // Grouping berdasarkan kebun
        // $dataPerKebun = $rekapKebun
        //     ->groupBy('kebun')
        //     ->map(function ($items) {
        //         return $items->groupBy('tgl');
        //     });

        // $sisa = [];
        // foreach ($dataPerKebun as $kebun => $tgl) {
        //     $first = $tgl->first()->first();
        //     $sisa[$kebun] = $first->kontrak;
        // }

        // return view('pengiriman.print.rencana', [
        //     'kontrakPengiriman' => $kontrakPengiriman,
        //     'tglPengiriman' => $tglPengiriman,
        //     'dataPerKebun' => $dataPerKebun,
        //     'tglChunks' => $tglChunks,
        //     'sisa' => $sisa,
        // ]);

        $rencanaPengiriman = RencanaPengiriman::query()
        ->join('rekap_kebun_pengiriman', 'rekap_kebun_pengiriman.id', '=', 'rencana_pengirimen.rekap_kebun_pengiriman_id')
        ->where('rekap_kebun_pengiriman.master_kontrak_pengiriman_id', $id)
        ->select(
            'rencana_pengirimen.*',
            'rekap_kebun_pengiriman.kontrak',
            'rekap_kebun_pengiriman.vendor',
            'rekap_kebun_pengiriman.kebun'
        )
        ->orderBy('tgl') // supaya urut saat dicetak
        ->get();

        return view('pengiriman.print.rencana', [
            'rencanaPengiriman' => $rencanaPengiriman,
        ]);
    }

}
