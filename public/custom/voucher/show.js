$(document).ready(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Ambil token dari meta tag
    const url = window.Laravel.closeUrl;
    $('.close-btn').on('click', function () {
        const $button = $(this); // Simpan referensi ke tombol
        $button.prop("disabled", true);
        $button.html("loading...");

        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                "X-CSRF-TOKEN": csrfToken
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
                });
                $button.prop("disabled", false);
                $button.html("Otorisasi");
            }
        });
    });
});
