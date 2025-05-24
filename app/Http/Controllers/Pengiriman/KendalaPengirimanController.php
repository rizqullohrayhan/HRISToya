<?php

namespace App\Http\Controllers\Pengiriman;

use App\Http\Controllers\Controller;
use App\Models\KendalaPengiriman;
use App\Traits\KontrakPengirimanTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendalaPengirimanController extends Controller
{
    use KontrakPengirimanTrait;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view kendala pengiriman')->only(['index', 'getKendalaPengirimanData', 'show', 'buildActionButtons']);
        $this->middleware('permission:add kendala pengiriman')->only(['create', 'store']);
        $this->middleware('permission:edit kendala pengiriman')->only(['edit', 'inlineEdit', 'update']);
        $this->middleware('permission:delete kendala pengiriman')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->kontrak_id;
            return $this->getKendalaPengirimanData($id);
        }
    }

    private function getKendalaPengirimanData($id)
    {
        $kendalaPengiriman = KendalaPengiriman::where('master_kontrak_pengiriman_id', $id)->get();

        return datatables()->of($kendalaPengiriman)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return $this->buildActionButtons($row);
            })
            ->addColumn('tgl_span', function ($row) {
                $tgl = $row->tgl ? Carbon::parse($row->tgl)->format('d/m/Y') : '&nbsp;&nbsp;&nbsp;&nbsp;';
                $value = $tgl == '' ? '' : $tgl;
                return '<span class="editable-date" data-id="'.$row->id.'" data-name="tgl" data-value="'.$value.'">'.$tgl.'</span>';
            })
            ->addColumn('uraian_span', function ($row) {
                $value = $row->uraian ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="uraian" data-value="'.($row->uraian ?? '').'">'.$value.'</span>';
            })
            ->rawColumns(['action', 'tgl_span', 'uraian_span'])
            ->make(true);
    }

    private function buildActionButtons($row)
    {
        $authUser = Auth::user();

        $btn = '';

        // if ($authUser->hasRole('ADM') || $authUser->hasPermissionTo('edit kendala pengiriman')) {
        //     $btn .= '
        //         <button data-id="' . $row->id . '" title="Edit" class="btn btn-link btn-warning btn-kendala-edit">
        //             <i class="fa fa-edit"></i>&nbsp;Edit
        //         </button>
        //     ';
        // }

        if ($authUser->hasRole('ADM') || $authUser->can('delete kendala pengiriman')) {
            $btn .= '
                <button type="button" data-id="' . $row->id . '" title="Hapus Kendala" class="btn btn-link btn-danger btn-destroy">
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl' => ['required'],
            'uraian' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
        ], [
            'tgl' => 'tanggal',
            'uraian' => 'Uraian',
        ]);

        KendalaPengiriman::create([
            'master_kontrak_pengiriman_id' => $request->kontrakId,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'uraian' => $request->uraian,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kendala pengiriman berhasil ditambahkan',
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
    public function edit(string $id)
    {
        $kendala = KendalaPengiriman::findOrFail($id);
        $kendala->tgl = $kendala->tgl ? Carbon::parse($kendala->tgl)->format('d/m/Y') : '';

        $data = [
            'title' => 'Tambah Kendala Pengiriman',
            'action' => route('kendala.store'),
            'kendala' => $kendala,
        ];
        return view('pengiriman.form-edit', $data);
    }

    public function inlineEdit(Request $request, string $id)
    {
        $kendala = KendalaPengiriman::findOrFail($id);
        $field = $request->input('name', $request->keys()[0]);
        $value = $request->input($field);

        if ($field === 'tgl') {
            $value = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

        $kendala->$field = $value;
        $kendala->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Detail Realisasi Pengiriman berhasil diupdate',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl' => ['required'],
            'uraian' => ['nullable', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
        ], [
            'tgl' => 'tanggal',
            'uraian' => 'Uraian',
        ]);

        $kendala = KendalaPengiriman::findOrFail($id);
        $kendala->update([
            'master_kontrak_pengiriman_id' => $request->kontrakId,
            'tgl' => $request->tgl ? Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d') : '',
            'uraian' => $request->uraian,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kendala pengiriman berhasil diupdate',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kendala = KendalaPengiriman::findOrFail($id);
        $kendala->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kendala Pengiriman Berhasil dihapus',
        ], 200);
    }
}
