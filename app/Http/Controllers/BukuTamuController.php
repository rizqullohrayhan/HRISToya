<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BukuTamuController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view buku tamu')->only(['index', 'get_data', 'show', 'accept', 'getFoto']);
        $this->middleware('permission:add buku tamu')->only(['create', 'store']);
        $this->middleware('permission:edit buku tamu')->only(['edit', 'update']);
        $this->middleware('permission:delete buku tamu')->only('destroy');
        $this->middleware('permission:confirm kedatangan tamu|confirm pulang tamu')->only(['accept', 'confirm', 'uploadFoto', 'deleteFoto', 'getFoto']);
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
        return view('buku_tamu.index' $data);
    }

    public function get_data(Request $request)
    {
        $start = Carbon::createFromFormat('d/m/Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $request->end)->format('Y-m-d');
        [$start, $end] = $this->parseDateRange($request);

        $data = BukuTamu::whereBetween('tgl', [$start, $end])->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $authUser = Auth::user();
                $btn = '
                            <a href="' . route('bukutamu.show', $row->id) . '" title="Edit" class="btn btn-link btn-info" data-original-title="Edit">
                                <i class="fa fa-eye"></i>&nbsp;Show
                            </a>
                        ';
                if ($authUser->hasPermissionTo('view buku tamu') || $authUser->hasRole('ADM')) {
                    $btn .= '
                                <a href="' . route('bukutamu.accept', $row->token) . '" title="Konfirmasi" class="btn btn-link btn-success" data-original-title="Edit">
                                    <i class="fa fa-check"></i>&nbsp;Konfirmasi
                                </a>
                            ';
                }
                if (($authUser->id == $row->created_by && $authUser->hasPermissionTo('edit buku tamu')) || $authUser->hasRole('ADM')) {
                    $btn .= '
                                <a href="' . route('bukutamu.edit', $row->id) . '" title="Edit" class="btn btn-link btn-warning" data-original-title="Edit">
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
            ->addColumn('tgl_show', function ($row) {
                return Carbon::parse($row->tgl)->translatedFormat('d F Y');
            })
            ->rawColumns(['action', 'tgl_show'])
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
        $isEditable = is_null($row->diperiksa_at);
        $buttons = '';

        $buttons .= '<a href="'. route('dinasluar.show', $row->id) .'" class="btn btn-link btn-primary"><i class="fa fa-eye"></i> Show</a>';

        if ($isAdmin || ($authUser->can('edit dinas luar') && $isOwnerOrCreator && $isEditable)) {
            $buttons .= '<a href="'. route('dinasluar.edit', $row->id) .'" class="btn btn-link btn-warning"><i class="fa fa-edit"></i> Edit</a>';
        }

        if ($isAdmin || ($authUser->can('delete dinas luar') && $isOwnerOrCreator && $isEditable)) {
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
        return view('buku_tamu.konfirmasi', $data);
    }

    public function confirm(Request $request, string $token)
    {
        $bukuTamu = BukuTamu::where('token', $token)->firstOrFail();
        $confirm = $request->confirm;
        if ($confirm == 'datang') {
            $request->validate([
                'id_card' => ['required', 'file', 'mimes:jpeg,jpg,png'],
                'foto_diri' => ['required', 'file', 'mimes:jpeg,jpg,png'],
                'surat_pengantar' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
                'kendaraan_tampak_depan' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
                'kendaraan_tampak_belakang' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
                'kendaraan_tampak_samping_kanan' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
                'kendaraan_tampak_samping_kiri' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
                'foto_peralatan' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            ], [
                'required' => ':attribute wajib diisi',
                'mimes' => ':attribute harus format jpeg,jpg,png',
                'file' => ':attribute harus berupa file',
            ], [
                'id_card' => 'Foto ID Card',
                'foto_diri' => 'Foto Diri',
                'surat_pengantar' => 'Surat Pengantar',
                'kendaraan_tampak_depan' => 'Kendaraan Tampak Depan',
                'kendaraan_tampak_belakang' => 'Kendaraan Tampak Belakang',
                'kendaraan_tampak_samping_kanan' => 'Kendaraan Tampak Samping Kanan',
                'kendaraan_tampak_samping_kiri' => 'Kendaraan Tampak Samping Kiri',
                'foto_peralatan' => 'Foto Peralatan',
            ]);
        }

        $message = 'Kunjungan berhasil dikonfirmasi';

        switch ($confirm) {
            case 'datang':
                $bukuTamu->id_card = $this->uploadFoto($request, $bukuTamu, 'id_card');
                $bukuTamu->foto_diri = $this->uploadFoto($request, $bukuTamu, 'foto_diri');
                $bukuTamu->surat_pengantar = $this->uploadFoto($request, $bukuTamu, 'surat_pengantar');
                $bukuTamu->kendaraan_tampak_depan = $this->uploadFoto($request, $bukuTamu, 'kendaraan_tampak_depan');
                $bukuTamu->kendaraan_tampak_belakang = $this->uploadFoto($request, $bukuTamu, 'kendaraan_tampak_belakang');
                $bukuTamu->kendaraan_tampak_samping_kanan = $this->uploadFoto($request, $bukuTamu, 'kendaraan_tampak_samping_kanan');
                $bukuTamu->kendaraan_tampak_samping_kiri = $this->uploadFoto($request, $bukuTamu, 'kendaraan_tampak_samping_kiri');
                $bukuTamu->foto_peralatan = $this->uploadFoto($request, $bukuTamu, 'foto_peralatan');
                $bukuTamu->datang = now();
                $bukuTamu->datang_by = Auth::user()->id;
                $bukuTamu->save();
                $message = 'Kedatangan berhasil dikonfirmasi';
                break;

            case 'batal datang':
                $bukuTamu->id_card = $this->deleteFoto($bukuTamu, 'id_card');
                $bukuTamu->foto_diri = $this->deleteFoto($bukuTamu, 'foto_diri');
                $bukuTamu->surat_pengantar = $this->deleteFoto($bukuTamu, 'surat_pengantar');
                $bukuTamu->kendaraan_tampak_depan = $this->deleteFoto($bukuTamu, 'kendaraan_tampak_depan');
                $bukuTamu->kendaraan_tampak_belakang = $this->deleteFoto($bukuTamu, 'kendaraan_tampak_belakang');
                $bukuTamu->kendaraan_tampak_samping_kanan = $this->deleteFoto($bukuTamu, 'kendaraan_tampak_samping_kanan');
                $bukuTamu->kendaraan_tampak_samping_kiri = $this->deleteFoto($bukuTamu, 'kendaraan_tampak_samping_kiri');
                $bukuTamu->foto_peralatan = $this->deleteFoto($bukuTamu, 'foto_peralatan');
                $bukuTamu->datang = null;
                $bukuTamu->datang_by = null;
                $bukuTamu->save();
                $message = 'Batal Datang berhasil dikonfirmasi';
                break;

            case 'pulang':
                $bukuTamu->pulang = now();
                $bukuTamu->pulang_by = Auth::user()->id;
                $bukuTamu->save();
                $message = 'Pulang berhasil dikonfirmasi';
                break;

            case 'batal pulang':
                $bukuTamu->pulang = null;
                $bukuTamu->pulang_by = null;
                $bukuTamu->save();
                $message = 'Batal Pulang berhasil dikonfirmasi';
                break;
        }

        $konfirmasi = view('buku_tamu._konfirmasi', compact('bukuTamu'))->render();
        return response()->json(['status' => 'success', 'message' => $message, 'render_table' => $konfirmasi]);
    }

    private function uploadFoto(Request $request, BukuTamu $bukuTamu, $field)
    {
        if ($request->hasFile($field)) {
            $image = $request->file($field);
            $imageName = $bukuTamu->token . '_' . time() . '.jpeg';
            $destinationPath = "bukutamu/$field/" . $imageName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            if (Storage::disk('local')->exists("bukutamu/$field/" . $bukuTamu->$field)) {
                Storage::disk('local')->delete("bukutamu/$field/" . $bukuTamu->$field);
            }
            Storage::disk('local')->put($destinationPath, $image->encodeByExtension('jpeg', quality: 20));
            return $imageName;
        }
        return null;
    }

    private function deleteFoto(BukuTamu $bukuTamu, $field)
    {
        if (Storage::disk('local')->exists("bukutamu/$field/" . $bukuTamu->$field)) {
            Storage::disk('local')->delete("bukutamu/$field/" . $bukuTamu->$field);
        }
        return null;
    }

    public function getFoto(Request $request, string $id)
    {
        $field = $request->foto;
        $bukuTamu = BukuTamu::findOrFail($id);
        if (Storage::disk('local')->exists("bukutamu/$field/" . $bukuTamu->$field)) {
            return response()->file(storage_path("app/private/bukutamu/$field/" . $bukuTamu->$field), [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline',
            ]);
        }
        return abort(404);
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
