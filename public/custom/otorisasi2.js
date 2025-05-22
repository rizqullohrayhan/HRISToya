$(document).ready(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const otorisasiUrl = window.Laravel.otorisasiUrl;
    let targetForUpload = null; // Untuk simpan target sementara saat klik tombol

    $('.otorisasi-btn').on('click', function () {
        const target = $(this).data('target');
        const $button = $(this);

        if ($button.attr('id') === 'btn-diterima' && $button.hasClass('otorisasi-mode')) {
            console.log('Tombol btn-diterima diklik!');
            targetForUpload = target; // Simpan target yang diklik
            $('#uploadModal').modal('show'); // Tampilkan modal upload
        }
        else if ($button.hasClass('otorisasi-mode')) {
            // Otorisasi biasa tanpa modal
            kirimOtorisasi(target);
        } else {
            // Hapus data
            kirimHapus(target);
        }
    });

    // Saat form upload di-submit
    $('#formUpload').on('submit', function (e) {
        e.preventDefault(); // Cegah reload halaman
        const formData = new FormData(this);
        formData.append('target', targetForUpload);
        formData.append('aksi', 'otorisasi');
        formData.append('_token', csrfToken);

        $.ajax({
            url: otorisasiUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                swal({
                    title: "Mohon Tunggu",
                    text: "Mengupload file dan memproses...",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    icon: "info"
                });
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

        // Tutup modal setelah submit
        $('#uploadModal').modal('hide');
    });

    function kirimOtorisasi(target) {
        $.ajax({
            url: otorisasiUrl,
            type: 'POST',
            data: {
                target: target,
                aksi: 'otorisasi',
                _token: csrfToken
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

    function kirimHapus(target) {
        $.ajax({
            url: otorisasiUrl,
            type: 'POST',
            data: {
                target: target,
                aksi: 'hapus',
                _token: csrfToken
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
