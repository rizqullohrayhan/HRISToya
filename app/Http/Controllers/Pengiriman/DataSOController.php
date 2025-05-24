<?php

namespace App\Http\Controllers\Pengiriman;

use App\Http\Controllers\Controller;
use App\Models\DataSO;
use App\Traits\KontrakPengirimanTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataSOController extends Controller
{
    use KontrakPengirimanTrait;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view data so')->only(['index', 'getDataSO', 'show', 'buildActionButtons']);
        $this->middleware('permission:add data so')->only(['create', 'store']);
        $this->middleware('permission:edit data so')->only(['edit', 'inlineEdit', 'update']);
        $this->middleware('permission:delete data so')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->kontrak_id;
            return $this->getDataSO($id);
        }
    }

    private function getDataSO($id)
    {
        $dataSO = DataSO::where('master_kontrak_pengiriman_id', $id)->get();

        return datatables()->of($dataSO)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($id) {
                return $this->buildActionButtons($row, $id);
            })
            ->addColumn('tgl_raw', fn($row) => $row->tgl)
            ->addColumn('nomor_raw', fn($row) => $row->nomor)
            ->addColumn('qty_raw', fn($row) => $row->qty)
            ->addColumn('sisa_raw', fn($row) => $row->sisa)
            ->editColumn('tgl', function ($row) {
                $tgl = $row->tgl ? Carbon::parse($row->tgl)->format('d/m/Y') : '&nbsp;&nbsp;&nbsp;&nbsp;';
                $value = $tgl == '' ? '' : $tgl;
                return '<span class="editable-date" data-id="'.$row->id.'" data-name="tgl" data-value="'.$value.'">'.$tgl.'</span>';
            })
            ->editColumn('nomor', function ($row) {
                $value = $row->nomor ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="nomor" data-value="'.($row->nomor).'">'.$value.'</span>';
            })
            ->editColumn('qty', function ($row) {
                $value = $row->qty !== null ? $row->qty : '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable format-number" data-id="'.$row->id.'" data-name="qty" data-value="'.($row->qty ?? 0).'">'.$value.'</span>';
            })
            ->editColumn('sisa', function ($row) {
                $value = $row->sisa !== null ? $row->sisa : '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable format-number" data-id="'.$row->id.'" data-name="sisa" data-value="'.($row->sisa ?? 0).'">'.$value.'</span>';
            })
            ->rawColumns(['tgl', 'action', 'nomor', 'qty', 'sisa'])
            ->make(true);
    }

    private function buildActionButtons($row, $kontrakId)
    {
        $authUser = Auth::user();

        $btn = '';

        // if ($authUser->hasRole('ADM') || $authUser->hasPermissionTo('edit data so')) {
        //     $btn .= '
        //         <a href="' . route('dataso.edit', $row->id) . '?kontrak='. $kontrakId .'" title="Edit" class="btn btn-link btn-warning">
        //             <i class="fa fa-edit"></i>&nbsp;Edit
        //         </a>
        //     ';
        // }

        if ($authUser->hasRole('ADM') || $authUser->can('delete data so')) {
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
        $data = [
            'kontrakId' => $request->kontrak,
        ];
        return view('pengiriman.so.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl' => ['required'],
            'nomor' => ['nullable', 'string'],
            'qty' => ['nullable', 'string'],
            'sisa' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
        ], [
            'tgl' => 'Tanggal',
            'nomor' => 'Nomer',
            'qty' => 'Qty',
            'sisa' => 'Sisa',
        ]);

        $kontrak = $request->kontrakId;

        DataSO::create([
            'master_kontrak_pengiriman_id' => $kontrak,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'nomor' => $request->nomor,
            'qty' => $request->qty,
            'sisa' => $request->sisa,
        ]);

        // $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data SO berhasil ditambahkan',
            // 'rekap_html' => view('pengiriman.kontrak_pengiriman._rekap_data', $rekapData)->render(),
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
        $dataSO = DataSO::findOrFail($id);
        $kontrak = $request->kontrak;

        $dataSO->tgl = $dataSO->tgl ? Carbon::parse($dataSO->tgl)->format('d/m/Y') : '';

        $data = [
            'dataSO' => $dataSO,
            'kontrakId' => $kontrak,
        ];
        return view('pengiriman.so.edit', $data);
    }

    public function inlineEdit(Request $request, string $id)
    {
        $dataSO = DataSO::findOrFail($id);
        $field = $request->input('name', $request->keys()[0]);
        $value = $request->input($field);

        if ($field === 'tgl') {
            $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

        $dataSO->$field = $value;
        $dataSO->save();

        // $updateRekapData = false;
        // $rekap_html = '';

        // if ($field === 'tgl' || $field === 'rekap_kebun_pengiriman_id') {
        //     $updateRekapData = true;
        //     $rekapData = $this->generateRekapData($request->kontrakId);
        //     $rekap_html = view('pengiriman.kontrak_pengiriman._rekap_data', $rekapData)->render();
        // }


        return response()->json([
            'status' => 'success',
            'message' => 'Data SO berhasil diupdate',
            // 'updateRekapData' => $updateRekapData,
            // 'rekap_html' => $rekap_html,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl' => ['required'],
            'nomor' => ['nullable', 'string'],
            'qty' => ['nullable', 'string'],
            'sisa' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
        ], [
            'tgl' => 'Tanggal',
            'nomor' => 'Nomer',
            'qty' => 'Qty',
            'sisa' => 'Sisa',
        ]);

        $dataSO = DataSO::findOrFail($id);
        $dataSO->update([
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'nomor' => $request->nomor,
            'qty' => $request->qty,
            'sisa' => $request->sisa,
        ]);

        $kontrak = $request->kontrakId;

        return redirect()->route('kontrak_pengiriman.show', $kontrak)->with('success', 'Data SO berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $dataSO = DataSO::findOrFail($id);
        $dataSO->delete();

        // $rekapData = $this->generateRekapData($request->kontrakId);

        return response()->json([
            'status' => 'success',
            'message' => 'Data SO Berhasil dihapus',
            // 'rekap_html' => view('pengiriman.kontrak_pengiriman._rekap_data', $rekapData)->render(),
        ], 200);
    }
}
