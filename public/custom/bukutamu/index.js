$(document).ready(function() {
    $( "#start-date" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
    });
    $( "#end-date" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
    });

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let start = $('#start-date').val();
    let end = $('#end-date').val();
    const urlData = window.Laravel.dataUrl;
    let html5QrCode;
    const qrRegionId = "qr-reader";

    const initializeDataTable = (start, end) => {
        return $('#table-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: `${urlData}?startdate=${start}&enddate=${end}`,
            columns: [
                {
                    data: "DT_RowIndex",
                    render: function (data, type, row) {
                        return data + '&nbsp;' + row.action; // Gabungkan nomor dengan tombol action
                    },
                    orderable: false, // Agar tidak bisa diurutkan
                    searchable: false, // Agar tidak bisa dicari
                },
                {data: "name"},
                {
                    data: "tgl",
                    render: function(data, type, row) {
                        return row.tgl_show; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "jam_awal",
                    render: function(data, type, row) {
                        return row.jam_awal + ' - ' + row.jam_akhir; // tampilkan HTML inline-edit
                    }
                },
                {data: "menemui"},
                {data: "keperluan"},
                {data: "telp"},
            ],
            order: [[2, "desc"], [3, "asc"]],
        })
    }

    let table = initializeDataTable(start, end);

    $('#filter').on('click', function () {
        start = encodeURIComponent($('#start-date').val() ?? '');
        end = encodeURIComponent($('#end-date').val() ?? '');
        table = initializeDataTable(start, end);
    });

    $('#table-datatable').on('click', '.btn-destroy', function(){
        swal({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak bisa mengembalikan data ini!",
            type: 'warning',
            buttons:{
                confirm: {
                    text : 'Ya, Hapus!',
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
                let id = $(this).data("id");
                let url = window.Laravel.deleteUrl.replace("__ID__", id);

                $.ajax({
                    url: url,
                    type: "POST",
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
                        swal({
                            title: "Berhasil!",
                            text: res.message,
                            icon: "success",
                            button: {
                                text: "OK",
                                className: "btn btn-success"
                            }
                        }).then(() => {
                            table.ajax.reload();
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
    })

    $('#scanqr').on('click', () => {$('#qrModal').modal('show')})

    function startQrScanner() {
        html5QrCode = new Html5Qrcode(qrRegionId);
        Html5Qrcode.getCameras().then(function (devices) {
            if (devices && devices.length) {
                html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    function (qrCodeMessage) {
                        $('#qr-reader-results').text("QR ditemukan: " + qrCodeMessage);

                        // Verifikasi link sebelum redirect
                        try {
                            const url = new URL(qrCodeMessage);
                            if (url.hostname === window.location.hostname) {
                                html5QrCode.stop().then(() => {
                                    html5QrCode.clear();
                                    window.location.href = qrCodeMessage;
                                });
                            } else {
                                alert("QR tidak valid.");
                                stopQrScanner();
                            }
                        } catch (e) {
                            alert("Data QR bukan URL valid.");
                            stopQrScanner();
                        }
                    },
                    function (errorMessage) {
                        console.log("QR Scan error:", errorMessage);
                    }
                );
            }
        }).catch(function (err) {
            console.error("Tidak bisa akses kamera:", err);
            alert("Gagal mengakses kamera.");
        });
    }

    function stopQrScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(err => {
                console.error("Error stop:", err);
            });
        }
    }

    // Event saat modal Bootstrap dibuka/tutup
    $('#qrModal').on('shown.bs.modal', function () {
        startQrScanner();
    });

    $('#qrModal').on('hidden.bs.modal', function () {
        stopQrScanner();
        $('#qr-reader-results').text('');
    });

})
