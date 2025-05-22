$(document).ready(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const confirmUrl = window.Laravel.url;

    $('#btn-datang').on('click', function() {
        $('#modalKonfirmasi').modal('show');
    })

    $('#btn-batal-datang').on('click', function() {
        sendConfirm('Semua foto terkait kunjungan ini akan dihapus', 'warning', 'Ya, Batalkan', 'batal datang');
    })

    $('#btn-pulang').on('click', function() {
        sendConfirm('Konfirmasi tamu kunjugan telah pulang', 'warning', 'Ya, Konfirmasi', 'pulang');
    })

    $('#btn-batal-pulang').on('click', function() {
        sendConfirm('Konfirmasi tamu kunjugan belum pulang', 'warning', 'Ya, Konfirmasi', 'batal pulang');
    })

    $('#form-kedatangan').submit(function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#modalKonfirmasi').modal('hide');
                window.location.reload();
            },
            error: function(res) {
                if (res.status === 422) {
                    const errors = res.responseJSON.errors;

                    $.each(errors, function(key, messages) {
                        const input = $(`[name="${key}"]`);

                        // Tambahkan kelas is-invalid untuk highlight merah (Bootstrap)
                        input.addClass('is-invalid');

                        // Tampilkan pesan error di bawah field
                        input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    });
                } else {
                    const message = res.responseJSON?.message || "Terjadi kesalahan";
                    swal("Error", message, {
                        icon : "error",
                        buttons: {
                            confirm: {
                                className : 'btn btn-danger'
                            }
                        },
                    });
                }
            }
        });
    })

    function sendConfirm(text, type, textConfirmButton, dataConfirm) {
        swal({
            title: 'Apakah Anda yakin?',
            text: text,
            icon: type,
            buttons:{
                confirm: {
                    text : textConfirmButton,
                    className : 'btn btn-success'
                },
                cancel: {
                    visible: true,
                    text : 'Tidak, batal!',
                    className: 'btn btn-danger'
                },
            },
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: confirmUrl,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    data: {
                        confirm: confirm,
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
                            tableMengetahui.ajax.reload();
                            $('#rekapKontrakPengiriman').html(res.rekap_html);
                        });
                    },
                    error: function (xhr) {
                        let err = JSON.parse(xhr.responseText);
                        swal({
                            title: "Gagal!",
                            text: err.message,
                            icon: "error",
                            button: {
                                text: "OK",
                                className: "btn btn-danger"
                            }
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                });
            } else {
                swal("Data batal dihapus!", {
                    buttons : {
                        confirm : {
                            className: 'btn btn-success'
                        }
                    }
                });
            }
        });
    }
})
