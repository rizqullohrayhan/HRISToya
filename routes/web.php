<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\BukuTamuController;
use App\Http\Controllers\CaraAktivitasController;
use App\Http\Controllers\Cuti\CutiBersamaController;
use App\Http\Controllers\Cuti\MacamCutiController;
use App\Http\Controllers\Cuti\CutiTahunanController;
use App\Http\Controllers\DinasLuarController;
use App\Http\Controllers\DinasLuarKotaController;
use App\Http\Controllers\IjinMasukPabrikController;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\KodePerkiraanController;
use App\Http\Controllers\MasterDokumenController;
use App\Http\Controllers\MataUangController;
use App\Http\Controllers\NotulenRapatController;
use App\Http\Controllers\Pengiriman\DataSOController;
use App\Http\Controllers\Pengiriman\DetailRealisasiController;
use App\Http\Controllers\Pengiriman\KendalaPengirimanController;
use App\Http\Controllers\Pengiriman\KontrakPengirimanController;
use App\Http\Controllers\Pengiriman\MengetahuiKontrakPengirimanController;
use App\Http\Controllers\Pengiriman\RencanaPengirimanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekananController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuratCutiController;
use App\Http\Controllers\SuratIjinController;
use App\Http\Controllers\SuratTugasKeluarController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TipeAktivitasController;
use App\Http\Controllers\TipeVoucherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/qr-code', [App\Http\Controllers\QRCodeController::class, 'download'])->name('qrcode');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/reset/password', [ProfileController::class, 'change_password'])->name('profile.change');
    Route::put('profile/reset/password', [ProfileController::class, 'reset_password'])->name('profile.reset');

    Route::group(['middleware' => ['permission:view absen']], function () {
        Route::resource('absen', AbsensiController::class)->except('destroy');
        Route::post('absen/{absen}/delete', [AbsensiController::class, 'destroy'])->name('absen.destroy');
        Route::get('absen/get/data', [AbsensiController::class, 'get_data'])->name('absen.data');
    });

    Route::group(['middleware' => ['permission:view aktivitas']], function () {
        Route::resource('aktivitas', AktivitasController::class)->except('destroy');
        Route::post('aktivitas/{aktivita}/delete', [AktivitasController::class, 'destroy'])->name('aktivitas.destroy');
        Route::get('aktivitas/get/data', [AktivitasController::class, 'get_data'])->name('aktivitas.data');
        Route::get('aktivitas/cetak/data', [AktivitasController::class, 'cetak'])->name('aktivitas.cetak');
        Route::get('aktivitas/download/file/{aktivita}', [AktivitasController::class, 'download'])->name('aktivitas.file');
    });

    Route::group(['middleware' => ['permission:view buku tamu']], function () {
        Route::resource('bukutamu', BukuTamuController::class)->except('destroy');
        Route::post('bukutamu/{bukutamu}/delete', [BukuTamuController::class, 'destroy'])->name('bukutamu.destroy');
        Route::get('bukutamu/get/data', [BukuTamuController::class, 'get_data'])->name('bukutamu.data');
        Route::get('bukutamu/{bukutamu}/accept', [BukuTamuController::class, 'accept'])->name('bukutamu.accept');
        Route::post('bukutamu/{bukutamu}/confirm', [BukuTamuController::class, 'confirm'])->name('bukutamu.confirm');
        Route::get('bukutamu/{bukutamu}/foto', [BukuTamuController::class, 'getFoto'])->name('bukutamu.foto');
        Route::get('bukutamu/download/file/{bukutamu}', [AktivitasController::class, 'download'])->name('bukutamu.file');
    });

    Route::group(['middleware' => ['permission:view voucher']], function () {
        Route::resource('voucher', VoucherController::class)->except('destroy');
        Route::get('voucher/get/data', [VoucherController::class, 'data'])->name('voucher.data');
        Route::post('voucher/{voucher}/delete', [VoucherController::class, 'destroy'])->name('voucher.destroy');
        Route::post('voucher/{voucher}/otoritas', [VoucherController::class, 'otoritas'])->name('voucher.update.otoritas');
        Route::post('voucher/{voucher}/close', [VoucherController::class, 'close'])->name('voucher.close');
        Route::get('voucher/{voucher}/cetak', [VoucherController::class, 'cetak'])->name('voucher.cetak');
        Route::get('voucher/download/bukti/{voucher}', [VoucherController::class, 'downloadBukti'])->name('voucher.bukti');
        Route::get('voucher/download/file/{voucher}', [VoucherController::class, 'downloadFile'])->name('voucher.file');
    });

    Route::group(['middleware' => ['permission:view surat ijin']], function () {
        Route::resource('ijin', SuratIjinController::class)->except('destroy');
        Route::get('ijin/get/data', [SuratIjinController::class, 'data'])->name('ijin.data');
        Route::post('ijin/{ijin}/delete', [SuratIjinController::class, 'destroy'])->name('ijin.destroy');
        Route::post('ijin/{ijin}/otoritas', [SuratIjinController::class, 'otoritas'])->name('ijin.update.otoritas');
        Route::post('ijin/{ijin}/close', [SuratIjinController::class, 'close'])->name('ijin.close');
        Route::get('ijin/{ijin}/cetak', [SuratIjinController::class, 'cetak'])->name('ijin.cetak');
    });

    Route::group(['middleware' => ['permission:view tugas keluar']], function () {
        Route::resource('tugas-keluar', SuratTugasKeluarController::class)->except('destroy');
        Route::get('tugas-keluar/get/data', [SuratTugasKeluarController::class, 'data'])->name('tugas-keluar.data');
        Route::post('tugas-keluar/{tugas_keluar}/delete', [SuratTugasKeluarController::class, 'destroy'])->name('tugas-keluar.destroy');
        Route::post('tugas-keluar/{tugas_keluar}/otoritas', [SuratTugasKeluarController::class, 'otoritas'])->name('tugas-keluar.update.otoritas');
        Route::post('tugas-keluar/{tugas_keluar}/close', [SuratTugasKeluarController::class, 'close'])->name('tugas-keluar.close');
        Route::get('tugas-keluar/{tugas_keluar}/cetak', [SuratTugasKeluarController::class, 'cetak'])->name('tugas-keluar.cetak');
    });

    Route::group(['middleware' => ['permission:view dinas luar']], function () {
        Route::resource('dinasluar', DinasLuarController::class)->except('destroy');
        Route::get('dinasluar/get/data', [DinasLuarController::class, 'data'])->name('dinasluar.data');
        Route::post('dinasluar/{dinasluar}/delete', [DinasLuarController::class, 'destroy'])->name('dinasluar.destroy');
        Route::post('dinasluar/{dinasluar}/otoritas', [DinasLuarController::class, 'otoritas'])->name('dinasluar.update.otoritas');
        Route::post('dinasluar/{dinasluar}/close', [DinasLuarController::class, 'close'])->name('dinasluar.close');
        Route::get('dinasluar/{dinasluar}/cetak', [DinasLuarController::class, 'cetak'])->name('dinasluar.cetak');
    });

    Route::group(['middleware' => ['permission:view dinas luar kota']], function () {
        Route::resource('dinasluarkota', DinasLuarKotaController::class)->except('destroy');
        Route::get('dinasluarkota/get/data', [DinasLuarKotaController::class, 'data'])->name('dinasluarkota.data');
        Route::post('dinasluarkota/{dinasluarkota}/delete', [DinasLuarKotaController::class, 'destroy'])->name('dinasluarkota.destroy');
        Route::post('dinasluarkota/{dinasluarkota}/otoritas', [DinasLuarKotaController::class, 'otoritas'])->name('dinasluarkota.update.otoritas');
        Route::post('dinasluarkota/{dinasluarkota}/close', [DinasLuarKotaController::class, 'close'])->name('dinasluarkota.close');
        Route::get('dinasluarkota/{dinasluarkota}/cetak', [DinasLuarKotaController::class, 'cetak'])->name('dinasluarkota.cetak');
    });

    Route::group(['middleware' => ['permission:view cuti']], function () {
        Route::resource('cuti', SuratCutiController::class)->except('destroy');
        Route::get('cuti/get/data', [SuratCutiController::class, 'data'])->name('cuti.data');
        Route::post('cuti/{cuti}/delete', [SuratCutiController::class, 'destroy'])->name('cuti.destroy');
        Route::post('cuti/{cuti}/otoritas', [SuratCutiController::class, 'otoritas'])->name('cuti.update.otoritas');
        Route::post('cuti/{cuti}/close', [SuratCutiController::class, 'close'])->name('cuti.close');
        Route::get('cuti/{cuti}/cetak', [SuratCutiController::class, 'cetak'])->name('cuti.cetak');
    });

    Route::group(['middleware' => ['permission:view ijin masuk pabrik']], function () {
        Route::resource('ijinpabrik', IjinMasukPabrikController::class)->except('destroy');
        Route::get('ijinpabrik/get/data', [IjinMasukPabrikController::class, 'data'])->name('ijinpabrik.data');
        Route::post('ijinpabrik/{ijinpabrik}/delete', [IjinMasukPabrikController::class, 'destroy'])->name('ijinpabrik.destroy');
        Route::post('ijinpabrik/{ijinpabrik}/otoritas', [IjinMasukPabrikController::class, 'otoritas'])->name('ijinpabrik.update.otoritas');
        Route::get('ijinpabrik/{ijinpabrik}/ktp', [IjinMasukPabrikController::class, 'showKTP'])->name('ijinpabrik.ktp');
        Route::get('ijinpabrik/{ijinpabrik}/kendaraan', [IjinMasukPabrikController::class, 'showKendaraan'])->name('ijinpabrik.kendaraan');
        Route::get('ijinpabrik/{ijinpabrik}/sim', [IjinMasukPabrikController::class, 'showSIM'])->name('ijinpabrik.sim');
        Route::get('ijinpabrik/{ijinpabrik}/cetak', [IjinMasukPabrikController::class, 'cetak'])->name('ijinpabrik.cetak');
    });

    Route::group(['middleware' => ['permission:view notulen rapat']], function () {
        Route::resource('notulen_rapat', NotulenRapatController::class)->except('destroy');
        Route::get('notulen_rapat/get/data', [NotulenRapatController::class, 'data'])->name('notulen_rapat.data');
        Route::post('notulen_rapat/{notulen_rapat}/delete', [NotulenRapatController::class, 'destroy'])->name('notulen_rapat.destroy');
        Route::post('notulen_rapat/{notulen_rapat}/otoritas', [NotulenRapatController::class, 'otoritas'])->name('notulen_rapat.update.otoritas');
        Route::post('notulen_rapat/{notulen_rapat}/close', [NotulenRapatController::class, 'close'])->name('notulen_rapat.close');
        Route::get('notulen_rapat/{notulen_rapat}/cetak', [NotulenRapatController::class, 'cetak'])->name('notulen_rapat.cetak');
    });

    Route::prefix('pengiriman')->group(function () {
        Route::group(['middleware' => ['permission:view kontrak pengiriman']], function () {
            Route::resource('kontrak', KontrakPengirimanController::class)->except('destroy');
            Route::post('kontrak/{kontrak}/delete', [KontrakPengirimanController::class, 'destroy'])->name('kontrak.destroy');
            Route::post('kontrak/{kontrak}/otoritas', [KontrakPengirimanController::class, 'otoritas'])->name('kontrak.update.otoritas');
            Route::get('kontrak/{kontrak}/cetak', [KontrakPengirimanController::class, 'cetak'])->name('kontrak.cetak');
        });
        Route::group(['middleware' => ['permission:view rencana pengiriman']], function () {
            Route::resource('rencana', RencanaPengirimanController::class)->except('destroy');
            Route::post('rencana/{rencana}/delete', [RencanaPengirimanController::class, 'destroy'])->name('rencana.destroy');
            Route::put('rencana/{rencana}/inlineEdit', [RencanaPengirimanController::class, 'inlineEdit'])->name('rencana.inlineEdit');
            Route::get('rencana/{rencana}/cetak', [RencanaPengirimanController::class, 'cetak'])->name('rencana.cetak');
        });
        Route::group(['middleware' => ['permission:view data so']], function () {
            Route::resource('dataso', DataSOController::class)->except('destroy');
            Route::post('dataso/{dataso}/delete', [DataSOController::class, 'destroy'])->name('dataso.destroy');
            Route::put('dataso/{dataso}/inlineEdit', [DataSOController::class, 'inlineEdit'])->name('dataso.inlineEdit');
        });
        Route::group(['middleware' => ['permission:view detail realisasi pengiriman']], function () {
            Route::resource('detail_realisasi', DetailRealisasiController::class)->except('destroy');
            Route::post('detail_realisasi/{detail_realisasi}/delete', [DetailRealisasiController::class, 'destroy'])->name('detail_realisasi.destroy');
            Route::put('detail_realisasi/{detail_realisasi}/inlineEdit', [DetailRealisasiController::class, 'inlineEdit'])->name('detail_realisasi.inlineEdit');
        });
        Route::group(['middleware' => ['permission:view kendala pengiriman']], function () {
            Route::resource('kendala', KendalaPengirimanController::class)->except('destroy');
            Route::post('kendala/{kendala}/delete', [KendalaPengirimanController::class, 'destroy'])->name('kendala.destroy');
            Route::put('kendala/{kendala}/inlineEdit', [KendalaPengirimanController::class, 'inlineEdit'])->name('kendala.inlineEdit');
        });
        Route::group(['middleware' => ['permission:view mengetahui pengiriman']], function () {
            Route::resource('mengetahui', MengetahuiKontrakPengirimanController::class)->except('destroy');
            Route::post('mengetahui/{mengetahui}/delete', [MengetahuiKontrakPengirimanController::class, 'destroy'])->name('mengetahui.destroy');
            Route::put('mengetahui/{mengetahui}/inlineEdit', [MengetahuiKontrakPengirimanController::class, 'inlineEdit'])->name('mengetahui.inlineEdit');
        });
    });

    Route::group(['middleware' => ['permission:view master dokumen']], function () {
        Route::resource('masterdokumen', MasterDokumenController::class)->except('destroy');
        Route::post('masterdokumen/{masterdokumen}/delete', [MasterDokumenController::class, 'destroy'])->name('masterdokumen.destroy');
        Route::get('masterdokumen/{masterdokumen}/download', [MasterDokumenController::class, 'download'])->name('masterdokumen.download');
    });

    Route::group(['middleware' => ['permission:view tipe aktivitas']], function () {
        Route::resource('tipeaktivitas', TipeAktivitasController::class)->except('destroy');
        Route::post('tipeaktivitas/{tipeaktivitas}/delete', [TipeAktivitasController::class, 'destroy'])->name('tipeaktivitas.destroy');
    });

    Route::group(['middleware' => ['permission:view cara aktivitas']], function () {
        Route::resource('caraaktivitas', CaraAktivitasController::class)->except('destroy');
        Route::post('caraaktivitas/{caraaktivitas}/delete', [CaraAktivitasController::class, 'destroy'])->name('caraaktivitas.destroy');
    });

    Route::group(['middleware' => ['permission:view permission']], function () {
        Route::resource('permission', PermissionController::class);
    });

    Route::group(['middleware' => ['permission:view role']], function () {
        Route::resource('role', RoleController::class);
        Route::get('role/{role}/permission', [RoleController::class, 'edit_permission'])->name('role.edit.permission');
        Route::put('role/{role}/permission', [RoleController::class, 'update_permission'])->name('role.update.permission');
    });

    Route::group(['middleware' => ['permission:view kode perkiraan']], function () {
        Route::resource('kodeperkiraan', KodePerkiraanController::class)->except('destroy');
        Route::post('kodeperkiraan/{kodeperkiraan}/delete', [KodePerkiraanController::class, 'destroy'])->name('kodeperkiraan.destroy');
    });

    Route::group(['middleware' => ['permission:view mata uang']], function () {
        Route::resource('matauang', MataUangController::class)->except('destroy');
        Route::post('matauang/{matauang}/delete', [MataUangController::class, 'destroy'])->name('matauang.destroy');
    });

    Route::group(['middleware' => ['permission:view tipe voucher']], function () {
        Route::resource('tipevoucher', TipeVoucherController::class)->except('destroy');
        Route::post('tipevoucher/{tipevoucher}/delete', [TipeVoucherController::class, 'destroy'])->name('tipevoucher.destroy');
    });

    Route::group(['middleware' => ['permission:view macam cuti']], function () {
        Route::resource('macamcuti', MacamCutiController::class)->except('destroy');
        Route::post('macamcuti/{macamcuti}/delete', [MacamCutiController::class, 'destroy'])->name('macamcuti.destroy');
    });

    Route::group(['middleware' => ['permission:view cuti bersama']], function () {
        Route::resource('cutibersama', CutiBersamaController::class);
        Route::get('cutibersama/get/date', [CutiBersamaController::class, 'data'])->name('cutibersama.data');
    });

    Route::group(['middleware' => ['permission:view cuti tahunan']], function () {
        Route::resource('cutitahunan', CutiTahunanController::class);
        Route::get('cutitahunan/get/date', [CutiTahunanController::class, 'data'])->name('cutitahunan.data');
    });

    Route::group(['middleware' => ['permission:view user']], function () {
        Route::resource('user', UserController::class)->except('destroy');
        Route::post('user/{user}/delete', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('/user/{id}/edit-permission', [UserController::class, 'edit_permission'])->name('user.edit.permission');
        Route::put('/user/{id}/update-permission', [UserController::class, 'update_permission'])->name('user.update.permission');
    });
    Route::group(['middleware' => ['permission:view team']], function () {
        Route::resource('team', TeamController::class)->except('destroy');
        Route::post('team/{team}/delete', [TeamController::class, 'destroy'])->name('team.destroy');
    });
    Route::group(['middleware' => ['permission:view rekan']], function () {
        Route::resource('rekan', RekananController::class)->except('destroy');
        Route::post('rekan/{rekan}/delete', [RekananController::class, 'destroy'])->name('rekan.destroy');
    });
    Route::group(['middleware' => ['permission:view kantor']], function () {
        Route::resource('kantor', KantorController::class)->except('destroy');
        Route::post('kantor/{kantor}/delete', [KantorController::class, 'destroy'])->name('kantor.destroy');
    });
});
