<?php

namespace App\Http\Controllers\Pengiriman;

use App\Http\Controllers\Controller;
use App\Models\MengetahuiKontrakPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MengetahuiKontrakPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->kontrak_id;
            return $this->getMengetahuiPengirimanData($id);
        }
    }

    private function getMengetahuiPengirimanData($id)
    {
        $mengetahuiPengiriman = MengetahuiKontrakPengiriman::where('master_kontrak_pengiriman_id', $id)->get();

        return datatables()->of($mengetahuiPengiriman)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return $this->buildActionButtons($row);
            })
            ->addColumn('name_span', function ($row) {
                $value = $row->name ?? '&nbsp;&nbsp;&nbsp;&nbsp;';
                return '<span class="editable" data-id="'.$row->id.'" data-name="name" data-value="'.($row->name ?? '').'">'.$value.'</span>';
            })
            ->rawColumns(['action', 'name_span'])
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

        if ($authUser->hasRole('ADM') || $authUser->hasPermissionTo('delete mengetahui pengiriman')) {
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kontrakId' => ['required', 'exists:master_kontrak_pengiriman,id'],
            'name' => ['required', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa string',
        ], [
            'kontrakId' => 'id',
            'name' => 'Nama',
        ]);

        $mengetahui = MengetahuiKontrakPengiriman::where('master_kontrak_pengiriman_id', $request->kontrakId)->count();

        if ($mengetahui >= 5) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mengetahui sudah mencapai batas maksimal',
            ], 400);
        }

        MengetahuiKontrakPengiriman::create([
            'master_kontrak_pengiriman_id' => $request->kontrakId,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MengetahuiKontrakPengiriman $mengetahuiKontrakPengiriman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MengetahuiKontrakPengiriman $mengetahuiKontrakPengiriman)
    {
        //
    }

    public function inlineEdit(Request $request, string $id)
    {
        $kendala = MengetahuiKontrakPengiriman::findOrFail($id);
        $field = $request->input('name');
        $value = $request->input('value');

        $kendala->$field = $value;
        $kendala->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MengetahuiKontrakPengiriman $mengetahuiKontrakPengiriman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MengetahuiKontrakPengiriman $mengetahuiKontrakPengiriman)
    {
        $mengetahuiKontrakPengiriman->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kendala Pengiriman Berhasil dihapus',
        ], 200);
    }
}
