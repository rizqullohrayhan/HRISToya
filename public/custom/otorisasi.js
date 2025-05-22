$(document).ready(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Ambil token dari meta tag
    const otorisasiUrl = window.Laravel.otorisasiUrl;
    $('.otorisasi-btn').on('click', function () {
        const target = $(this).data('target'); // Ambil target kolom (dibuat, mengetahui, dll.)
        const $button = $(this); // Simpan referensi ke tombol

        $button.prop("disabled", true);
        $button.html("loading...");

        // Otorisasi atau hapus berdasarkan kelas tombol
        if ($button.hasClass('otorisasi-mode')) {
            // Kirim data otorisasi ke server via AJAX

            $.ajax({
                url: otorisasiUrl,  // Endpoint server untuk otorisasi
                type: 'POST',
                data: {
                    target: target,
                    aksi: 'otorisasi',
                    _token: csrfToken // Token CSRF untuk keamanan Laravel
                },
                beforeSend: function () {
                    swal({
                        title: "Mohon Tunggu",
                        text: "Sedang memproses...",
                        buttons: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        icon: "info"
                    });
                },
                success: function (res) {
                    // Jika sukses, update tampilan
                    swal({
                        title: "Berhasil!",
                        text: res.message,
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "btn btn-success"
                        }
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    // Tampilkan pesan error jika gagal
                    swal({
                        title: "Gagal!",
                        text: xhr.responseText,
                        icon: "error",
                        button: {
                            text: "OK",
                            className: "btn btn-success"
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        } else {
            // Hapus data dari server
            $.ajax({
                url: otorisasiUrl,
                type: 'POST',
                data: {
                    target: target,
                    aksi: 'hapus',
                    _token: csrfToken // Token CSRF
                },
                success: function (res) {
                    swal({
                        title: "Berhasil!",
                        text: res.message,
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "btn btn-success"
                        }
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    swal({
                        title: "Gagal!",
                        text: xhr.responseText,
                        icon: "error",
                        button: {
                            text: "OK",
                            className: "btn btn-success"
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
});
