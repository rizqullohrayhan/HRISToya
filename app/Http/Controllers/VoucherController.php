<?php

namespace App\Http\Controllers;

use App\Models\DetailVoucher;
use App\Models\KodePerkiraan;
use App\Models\MataUang;
use App\Models\Rekanan;
use App\Models\StatusVoucher;
use App\Models\TipeVoucher;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class VoucherController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public function __construct()
    {
        $this->middleware('permission:view voucher')->only(['index', 'data', 'show', 'otoritas', 'cetak']);
        $this->middleware('permission:add voucher')->only(['create', 'store', 'otoritas', 'generateFilename']);
        $this->middleware('permission:edit voucher')->only(['edit', 'update', 'otoritas', 'generateFilename']);
        $this->middleware('permission:delete voucher')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = \Carbon\Carbon::today();
        if ($today->day < 28) {
            $start = $today->copy()->subMonthNoOverflow()->day(28); // 28 bulan lalu
            $end = $today->copy()->day(27);                          // 27 bulan ini
        } else {
            $start = $today->copy()->day(28);                        // 28 bulan ini
            $end = $today->copy()->addMonthNoOverflow()->day(27);    // 27 bulan depan
        }

        $data = [
            'start' => $start->format('d/m/Y'),
            'end' => $end->format('d/m/Y'),
        ];
        return view('voucher.index', $data);
    }

    public function data(Request $request)
    {
        [$start, $end] = $this->parseDateRange($request);
        $authUser = Auth::user();

        $surat = Voucher::with(['statusVoucher', 'bankCode', 'rekanan', 'tipeVoucher'])->whereBetween('tanggal', [$start, $end]);

        if ($authUser->hasRole('ADM')) {
            $surat = $surat->get();
        } else {
            $surat = $surat->where(function ($query) use ($authUser) {
                    $query->where('user_id', $authUser->id)
                        ->orWhere('created_by', $authUser->id);
                })
                ->get();
        }

        return DataTables::of($surat)
            ->addIndexColumn()
            ->addColumn('action', fn($row) => $this->buildActionButtons($row, $authUser))
            ->editColumn('tanggal', fn($row) => Carbon::parse($row->tanggal)->format('d/m/Y'))
            ->rawColumns(['action', 'tanggal'])
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
        $buttons = '';

        $buttons .= '<a href="'. route('voucher.show', $row->id) .'" class="btn btn-link btn-primary"><i class="fa fa-eye"></i> Show</a>';

        if (($authUser->can('edit voucher') && ($authUser->id == $row->user_id || $authUser->id == $row->created_by) && is_null($row->reviewed_at)) ||
            $authUser->hasRole('ADM'))
        {
            $buttons .= '<a href="'. route('voucher.edit', $row->id) .'" class="btn btn-link btn-warning"><i class="fa fa-edit"></i> Edit</a>';
        }

        if (($authUser->can('delete voucher') && ($authUser->id == $row->user_id || $authUser->id == $row->created_by) && is_null($row->reviewed_at)) ||
            $authUser->hasRole('ADM'))
        {
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
        $status = StatusVoucher::all();
        $tipe = TipeVoucher::all();
        $rekan = Rekanan::all();
        $perkiraan = KodePerkiraan::all();
        $mataUang = MataUang::all();
        $data = [
            'status' => $status,
            'tipes' => $tipe,
            'rekans' => $rekan,
            'perkiraans' => $perkiraan,
            'mataUangs' => $mataUang,
        ];
        return view('voucher.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => ['required', 'string'],
            'status_id' => ['required', 'exists:status_vouchers,id'],
            'bank_code_id' => ['required', 'exists:kode_perkiraans,id'],
            'rekan_id' => ['required', 'exists:rekanans,id'],
            'pay_for' => ['required', 'string'],
            'tipe_id' => ['required', 'exists:tipe_vouchers,id'],
            'details' => ['required', 'array', 'min:1'], // Harus ada minimal satu detail
            'details.*.bank_code_id' => ['required', 'exists:kode_perkiraans,id'],
            'details.*.code' => ['required'],
            'details.*.name' => ['nullable', 'string'],
            'details.*.currency_id' => ['nullable', 'exists:mata_uangs,id'],
            'details.*.amount' => ['nullable'],
            'details.*.uraian' => ['nullable', 'string'],
            'details.*.no_bukti' => ['nullable', 'string', 'max:50'],
            'details.*.tgl_bukti' => ['nullable', 'string'],
            'details.*.rekan_id' => ['nullable', 'exists:rekanans,id'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'dibuat_at' => ['nullable'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'mengetahui_at' => ['nullable'],
            'pembukuan_by' => ['nullable', 'exists:users,id'],
            'pembukuan_at' => ['nullable'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'disetujui_at' => ['nullable'],
        ], [
            'required' => ':attribute wajib diisi',
            'exist' => ':attribute tidak terdaftar didatabase',
            'file' => ':attribute harus berupa file',
        ], [
            'status_id' => 'Status',
            'code' => 'Kode Kas/Bank',
            'rekan_id' => 'Rekanan',
            'pay_for' => 'Dibayar Untuk',
            'tipe_id' => 'Tipe',
            'file' => 'Dokumen Pendukung',
            'dibuat_by' => 'Dibuat',
            'mengetahui_by' => 'Mengetahui',
            'pembukuan_by' => 'Pembukuan',
            'disetujui_by' => 'Disetujui',
        ]);

        $no_voucher = DB::transaction(function () use ($request) {
            $bulan = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('m');
            // Ambil nomor terakhir dengan mengunci baris (hindari race condition)
            $lastRecord = DB::table('vouchers')->lockForUpdate()->where('no_voucher', 'LIKE', "VK-%/$bulan")->latest('id')->first();

            // Ambil nomor urut terakhir dan tambahkan 1
            if ($lastRecord) {
                $lastNumber = (int) substr($lastRecord->no_voucher, 3, 4); // Ambil 4 digit nomor urut
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Tambah 1, lalu pad dengan 0
            } else {
                $newNumber = '0001'; // Jika belum ada, mulai dari 0001
            }

            return "VK-$newNumber/$bulan"; // Format akhir
        });

        // dd($no_voucher);

        $voucher = Voucher::create([
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
            'status_id' => $request->status_id,
            'no_voucher' => $no_voucher,
            'bank_code_id' => $request->bank_code_id,
            'rekan_id' => $request->rekan_id,
            'pay_for' => $request->pay_for,
            'tipe_id' => $request->tipe_id,
            'user_id' => $request->dibuat_by,
            'set_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'reviewed_by' => $request->mengetahui_by,
            'reviewed_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'bookkeeped_by' => $request->pembukuan_by,
            'bookkeeped_at' => $request->pembukuan_at ? Carbon::parse($request->pembukuan_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'approved_by' => $request->disetujui_by,
            'approved_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'created_by' => Auth::user()->id,
        ]);

        foreach ($validatedData['details'] as $detail) {
            DetailVoucher::create([
                'voucher_id' => $voucher->id,
                'bank_code_id' => $detail['bank_code_id'],
                'perkiraan_id' => $detail['code'] ?? null,
                'currency_id' => $detail['currency_id'] ?? null,
                'amount' => $detail['amount'],
                'uraian' => $detail['uraian'],
                'no_bukti' => $detail['no_bukti'],
                'tgl_bukti' => $detail['tgl_bukti'] ? Carbon::createFromFormat('d/m/Y', $detail['tgl_bukti'])->format('Y-m-d') : null,
                'rekan_id' => $detail['rekan_id'] ?? null,
            ]);
        }

        return redirect()->route('voucher.show', $voucher->id)->with('success', "Voucher $no_voucher berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::with(['statusVoucher', 'bankCode', 'rekanan', 'tipeVoucher', 'user', 'reviewer', 'bookkeeper', 'approver', 'detailVoucher'])
            ->where('id', $id)
            ->firstOrFail();
        $detailVoucher = DetailVoucher::with(['bankCode', 'perkiraan', 'mataUang', 'rekanan'])
                                        ->where('voucher_id', $id)
                                        ->get();
        $data = [
            'voucher' => $voucher,
            'detailVoucher' => $detailVoucher,
        ];
        return view('voucher.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $voucher = Voucher::with(['statusVoucher', 'bankCode', 'rekanan', 'tipeVoucher', 'user', 'reviewer', 'bookkeeper', 'approver', 'detailVoucher'])
            ->where('id', $id)
            ->firstOrFail();
        if (!is_null($voucher->reviewed_by) && !Auth::user()->hasRole('ADM')) {
            // abort(403);
            return redirect()->back()->with('error', 'Tidak dapat mengedit karena voucher telah di otorisasi');
        }
        $detailVoucher = DetailVoucher::with(['bankCode', 'perkiraan', 'mataUang', 'rekanan'])
                                        ->where('voucher_id', $id)
                                        ->get();
        $status = StatusVoucher::all();
        $tipe = TipeVoucher::all();
        $rekan = Rekanan::all();
        $perkiraan = KodePerkiraan::all();
        $mataUang = MataUang::all();
        $data = [
            'voucher' => $voucher,
            'detailVoucher' => $detailVoucher,
            'status' => $status,
            'tipes' => $tipe,
            'rekans' => $rekan,
            'perkiraans' => $perkiraan,
            'mataUangs' => $mataUang,
        ];
        // dd($data);
        return view('voucher.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->input());
        $validatedData = $request->validate([
            'tanggal' => ['required', 'string'],
            'status_id' => ['required', 'exists:status_vouchers,id'],
            'bank_code_id' => ['required', 'exists:kode_perkiraans,id'],
            'rekan_id' => ['required', 'exists:rekanans,id'],
            'pay_for' => ['required', 'string'],
            'tipe_id' => ['required', 'exists:tipe_vouchers,id'],
            'details' => ['required', 'array', 'min:1'], // Harus ada minimal satu detail
            'details.*.bank_code_id' => ['required', 'exists:kode_perkiraans,id'],
            'details.*.code' => ['required'],
            'details.*.name' => ['nullable', 'string'],
            'details.*.currency_id' => ['nullable', 'exists:mata_uangs,id'],
            'details.*.amount' => ['nullable'],
            'details.*.uraian' => ['nullable', 'string'],
            'details.*.no_bukti' => ['nullable', 'string', 'max:50'],
            'details.*.tgl_bukti' => ['nullable', 'string'],
            'details.*.rekan_id' => ['nullable', 'exists:rekanans,id'],
            'dibuat_by' => ['nullable', 'exists:users,id'],
            'dibuat_at' => ['nullable'],
            'mengetahui_by' => ['nullable', 'exists:users,id'],
            'mengetahui_at' => ['nullable'],
            'pembukuan_by' => ['nullable', 'exists:users,id'],
            'pembukuan_at' => ['nullable'],
            'disetujui_by' => ['nullable', 'exists:users,id'],
            'disetujui_at' => ['nullable'],
        ], [
            'required' => ':attribute wajib diisi',
            'exist' => ':attribute tidak terdaftar didatabase',
            'file' => ':attribute harus berupa file',
        ], [
            'status_id' => 'Status',
            'code' => 'Kode Kas/Bank',
            'rekan_id' => 'Rekanan',
            'pay_for' => 'Dibayar Untuk',
            'tipe_id' => 'Tipe',
            'file' => 'Dokumen Pendukung',
            'dibuat_by' => 'Dibuat',
            'mengetahui_by' => 'Mengetahui',
            'pembukuan_by' => 'Pembukuan',
            'disetujui_by' => 'Disetujui',
        ]);

        $voucher = Voucher::findOrFail($id);

        $voucher->update([
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
            'status_id' => $request->status_id,
            'bank_code_id' => $request->bank_code_id,
            'rekan_id' => $request->rekan_id,
            'pay_for' => $request->pay_for,
            'tipe_id' => $request->tipe_id,
            'user_id' => $request->dibuat_by,
            'set_at' => $request->dibuat_at ? Carbon::parse($request->dibuat_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'reviewed_by' => $request->mengetahui_by,
            'reviewed_at' => $request->mengetahui_at ? Carbon::parse($request->mengetahui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'bookkeeped_by' => $request->pembukuan_by,
            'bookkeeped_at' => $request->pembukuan_at ? Carbon::parse($request->pembukuan_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            'approved_by' => $request->disetujui_by,
            'approved_at' => $request->disetujui_at ? Carbon::parse($request->disetujui_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
        ]);

        // Ambil semua ID detail voucher yang ada di database
        $existingDetailIds = DetailVoucher::where('voucher_id', $voucher->id)->pluck('id')->toArray();

        // Ambil semua ID detail yang dikirim dari form (hanya ID yang sudah ada)
        $formDetailIds = collect($request->details)->pluck('id')->filter()->toArray();

        // Cari data yang harus dihapus (ID di database tapi tidak ada di form)
        $toDelete = array_diff($existingDetailIds, $formDetailIds);
        DetailVoucher::whereIn('id', $toDelete)->delete();

        // Loop data detail dari form
        foreach ($request->details as $detail) {
            if (isset($detail['id'])) {
                // Jika ID ada di form, update detail lama
                DetailVoucher::where('id', $detail['id'])->update([
                    'bank_code_id' => $detail['bank_code_id'],
                    'perkiraan_id' => $detail['code'] ?? null,
                    'currency_id' => $detail['currency_id'] ?? null,
                    'amount' => $detail['amount'],
                    'uraian' => $detail['uraian'],
                    'no_bukti' => $detail['no_bukti'],
                    'tgl_bukti' => $detail['tgl_bukti'] ? Carbon::createFromFormat('d/m/Y', $detail['tgl_bukti'])->format('Y-m-d') : null,
                    'rekan_id' => $detail['rekan_id'] ?? null,
                ]);
            } else {
                // Jika ID tidak ada di form, buat detail baru
                DetailVoucher::create([
                    'voucher_id' => $voucher->id,
                    'bank_code_id' => $detail['bank_code_id'],
                    'perkiraan_id' => $detail['code'] ?? null,
                    'currency_id' => $detail['currency_id'] ?? null,
                    'amount' => $detail['amount'],
                    'uraian' => $detail['uraian'],
                    'no_bukti' => $detail['no_bukti'],
                    'tgl_bukti' => $detail['tgl_bukti'] ? Carbon::createFromFormat('d/m/Y', $detail['tgl_bukti'])->format('Y-m-d') : null,
                    'rekan_id' => $detail['rekan_id'] ?? null,
                ]);
            }
        }

        return redirect()->route('voucher.show', $voucher->id)->with('success', 'Ajuan Voucher berhasil diupdate');
    }

    public function otoritas(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'target' => ['required'],
            'aksi' => ['required', Rule::in(['otorisasi', 'hapus']),],
        ]);

        $voucher = Voucher::findOrFail($id);
        $field = $request->target;
        $update = [];
        $data = [];

        // Urutan field otorisasi yang harus diperiksa
        $authorizationOrder = [
            ['name' => 'Dibuat', 'field' => 'user_id'],
            ['name' => 'Mengetahui', 'field' => 'reviewed_by'],
            ['name' => 'Pembukuan', 'field' => 'bookkeeped_by'],
            ['name' => 'Disetujui', 'field' => 'approved_by']
        ];

        // Cari posisi field yang sedang diotorisasi di dalam array urutan
        $currentField = $field == 'user' ? 'user_id' : $field . '_by';
        $fieldIndex = array_search($currentField, array_column($authorizationOrder, 'field'));

        if ($request->aksi == 'otorisasi') {
            // Cek apakah field sebelumnya sudah terisi, jika ada field sebelumnya
            if ($fieldIndex > 0) {
                $previousField = $authorizationOrder[$fieldIndex - 1];  // Field sebelumnya dalam urutan
                if (empty($voucher[$previousField['field']])) {
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (".$previousField['name'].") belum terisi.", 400);
                }
            }

            // Cek apakah field sudah terisi
            if ($field == 'user' && !empty($voucher['user_id'])) {
                $currentFieldName = $authorizationOrder[$fieldIndex]['name'];
                return response()->json("Gagal otorisasi, Otorisasi $currentFieldName sudah terisi.", 400);
            } elseif (!empty($voucher[$field . '_by'])) {
                $currentFieldName = $authorizationOrder[$fieldIndex]['name'];
                return response()->json("Gagal otorisasi, Otorisasi $currentFieldName sudah terisi.", 400);
            }

            $user = Auth::user()->id;
            $time = Carbon::now()->format('Y-m-d H:i:s');
            $data['name'] = Auth::user()->name;
            $data['role'] = Auth::user()->roles->pluck('name')[0];
            $data['time'] = $time;
        } else {
            // Cek apakah field sebelumnya sudah terisi, jika ada field sebelumnya
            if ($fieldIndex < 3) {
                $nextField = $authorizationOrder[$fieldIndex + 1];  // Field sebelumnya dalam urutan
                if (!empty($voucher[$nextField['field']])) {
                    return response()->json("Gagal otorisasi, Otorisasi sebelumnya (".$nextField['name'].") telah terotorisasi.", 400);
                }
            }
            $user = null;
            $time = null;
        }

        if ($field == "user") {
            $update["user_id"] = $user;
            $update["set_at"] = $time;
        } else {
            $update[$field . "_by"] = $user;
            $update[$field . "_at"] = $time;
        }

        $voucher->update($update);

        return response()->json(['status' => 'success', 'message' => 'Otorisasi Voucher Berhasil diupdate', 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kasbon = Voucher::findOrFail($id);
        if (
            Auth::user()->id != $kasbon->user_id
            && Auth::user()->id != $kasbon->created_by
            && !Auth::user()->hasRole('ADM')) {
            abort(403);
        }
        if (!is_null($kasbon->file) && Storage::disk('local')->exists('kasbon/' . $kasbon->file)) {
            Storage::disk('local')->delete('kasbon/' . $kasbon->file);
        }
        if (!is_null($kasbon->bukti) && Storage::disk('local')->exists('kasbon/' . $kasbon->bukti)) {
            Storage::disk('local')->delete('kasbon/' . $kasbon->bukti);
        }
        $kasbon->delete();
        return response()->json(['status' => 'success', 'message' => 'Voucher Berhasil dihapus'], 200);
    }

    public function close(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['status_id' => 2]);
        return response()->json(['status' => 'success', 'message' => "Ajuan Voucher Berhasil ditutup"], 200);
    }

    public function cetak(Request $request, $id)
    {
        $voucher = Voucher::with(['statusVoucher', 'bankCode', 'rekanan', 'tipeVoucher', 'user', 'reviewer', 'bookkeeper', 'approver', 'detailVoucher'])
                            ->where('id', $id)
                            ->firstOrFail();
        $detailVouchers = DetailVoucher::with(['bankCode', 'perkiraan', 'mataUang', 'rekanan'])
                                        ->where('voucher_id', $id)
                                        ->get();
        $amounts = $detailVouchers->pluck('amount')->map(function ($value) {
            return (float)$value;  // Cast nilai dari varchar ke float
        });
        $totalJumlah = $amounts->sum();
        $data = [
            'voucher' => $voucher,
            'detailVouchers' => $detailVouchers,
            'totalJumlah' => $totalJumlah,
        ];
        return view('voucher.cetak', $data);
    }

    private function generateFilename()
    {
        $filename = Str::random(15);
        if (Storage::disk('local')->exists('kasbon/' . $filename)) {
            $filename = generateFilename();
        }

        return $filename;
    }
}
