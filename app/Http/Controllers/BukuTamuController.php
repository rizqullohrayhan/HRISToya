<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use Carbon\Carbon;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class BukuTamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('buku_tamu.index');
    }

    public function get_data(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $data = BukuTamu::whereBetween('tgl', [$start, $end])->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $authUser = Auth::user();
                $btn = '
                            <a href="' . route('bukutamu.show', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if (($authUser->id == $row->created_by && $authUser->hasPermissionTo('edit buku tamu')) || $authUser->hasRole('ADM')) {
                    $btn .= '
                                <a href="' . route('bukutamu.edit', $row->id) . '" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>&nbsp;Edit
                                </a>
                            ';
                }
                if (($authUser->id == $row->created_by && $authUser->hasPermissionTo('delete buku tamu')) || $authUser->hasRole('ADM')) {
                    $btn .= '
                                <button type="button" data-id="' . $row->id . '" title="Hapus buku tamu" class="btn btn-link btn-danger btn-destroy" data-original-title="Remove">
                                    <i class="fa fa-times"></i>&nbsp;Hapus
                                </button>
                            ';
                }
                return '
                            <div class="form-button-action">
                                <div class="btn-group dropdown">
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
            ->addColumn('tgl_show', function ($row) {
                return Carbon::parse($row->tgl)->translatedFormat('d F Y');
            })
            ->rawColumns(['action', 'tgl_show'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buku_tamu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl' => ['required', 'date_format:d/m/Y'],
            'jam_awal' => ['required', 'date_format:H:i'],
            'jam_akhir' => ['nullable', 'date_format:H:i'],
            'name' => ['required', 'string'],
            'telp' => ['nullable', 'string'],
            'instansi' => ['nullable', 'string'],
            'alamat' => ['nullable', 'string'],
            'menemui' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'date_format' => 'format :attribute tidak sesuai',
        ], [
            'tgl' => 'tanggal',
            'jam_awal' => 'Jam Awal Kunjungan',
            'jam_akhir' => 'Jam Akhir Kunjungan',
            'name' => 'Nama Tamu',
            'telp' => 'No Telp',
            'instansi' => 'Instansi',
            'alamat' => 'alamat',
            'menemui' => 'Menemui',
            'keperluan' => 'Keperluan',
        ]);

        $request->merge([
            'tgl' => Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d'),
            'created_by' => Auth::user()->id
        ]);

        $bukuTamu = BukuTamu::create($request->except('_token'))->refresh();

        return redirect()->route('bukutamu.show', $bukuTamu->id)->with('success', 'Kunjungan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bukuTamu = BukuTamu::findOrFail($id);
        $url = route('bukutamu.accept', $bukuTamu->token);
        $data = [
            'bukuTamu' => $bukuTamu,
            'url' => $url,
        ];
        return view('buku_tamu.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bukuTamu = BukuTamu::findOrFail($id);
        return view('buku_tamu.edit', ['bukuTamu' => $bukuTamu]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl' => ['required', 'date_format:d/m/Y'],
            'jam_awal' => ['required', 'date_format:H:i'],
            'jam_akhir' => ['nullable', 'date_format:H:i'],
            'name' => ['required', 'string'],
            'telp' => ['nullable', 'string'],
            'instansi' => ['nullable', 'string'],
            'alamat' => ['nullable', 'string'],
            'menemui' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
        ], [
            'required' => ':attribute wajib diisi',
            'date_format' => 'format :attribute tidak sesuai',
        ], [
            'tgl' => 'tanggal',
            'jam_awal' => 'Jam Awal Kunjungan',
            'jam_akhir' => 'Jam Akhir Kunjungan',
            'name' => 'Nama Tamu',
            'telp' => 'No Telp',
            'instansi' => 'Instansi',
            'alamat' => 'alamat',
            'menemui' => 'Menemui',
            'keperluan' => 'Keperluan',
        ]);

        $bukuTamu = BukuTamu::findOrFail($id);
        $bukuTamu->update($request->except('_token', '_method'));

        return redirect()->route('bukutamu.show', $bukuTamu->id)->with('success', 'Kunjungan berhasil diupdate');
    }

    public function accept(string $token)
    {
        $bukuTamu = BukuTamu::where('token', $token)->firstOrFail();
        $data = [
            'bukuTamu' => $bukuTamu,
        ];
        return view('bukuTamu.konfirmasi', $data);
    }

    public function confirm(Request $request, string $token)
    {
        $bukuTamu = BukuTamu::where('token', $token)->firstOrFail();
        $confirm =  $request->confirm;
        if ($confirm == 'datang') {
            $request->validate([
                'id_card' => ['required', 'image'],
                'foto_diri' => ['required', 'image'],
                'surat_pengantar' => ['nullable', 'image'],
                'kendaraan_tampak_depan' => ['nullable', 'image'],
                'kendaraan_tampak_belakang' => ['nullable', 'image'],
                'kendaraan_tampak_samping_kanan' => ['nullable', 'image'],
                'kendaraan_tampak_samping_kiri' => ['nullable', 'image'],
                'foto_peralatan' => ['nullable', 'image'],
            ]);
        }

        $data = [
            'bukuTamu' => $bukuTamu,
        ];
        return view('bukuTamu.konfirmasi', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bukuTamu = BukuTamu::findOrFail($id);
        $bukuTamu->delete();
        return response()->json(['status' => 'success', 'message' => 'Kunjungan berhasil dihapus']);
    }
}
