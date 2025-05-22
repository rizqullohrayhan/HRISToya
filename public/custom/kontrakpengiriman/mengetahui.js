$(document).ready(function() {
// --------------------
// Tabel Mengetahui Pengiriman
// --------------------
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const initializeDataTableMengetahui = () => {
        return $('#table-mengetahui').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: window.Laravel.mengetahuiUrl,
                type: 'GET',
                data: {
                    kontrak_id: window.Laravel.kontrakId,
                }
            },
            columns: [
                {data: "DT_RowIndex"},
                {
                    data: "name",
                    render: function(data, type, row) {
                        return row.name_span; // tampilkan HTML inline-edit
                    }
                },
                {data: "action", orderable: false, searchable: false},
            ],
            scrollResize: true,
            scrollY: 300,
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            searching: false,
            dom: 'lrtip',
            ordering: false,
            info: false,
        });
    }

    let tableMengetahui = initializeDataTableMengetahui();

    const addMengetahuiForm = $('#add-mengetahui-form');

    function resetAddMengetahuiForm() {
        const form = $('#add-mengetahui-form')[0];

        // Reset form standar (input, select, dll)
        form.reset();

        // Reset flatpickr (jika pakai Flatpickr)
        if ($('#add-tgl-so')[0]._flatpickr) {
            $('#add-tgl-so')[0]._flatpickr.clear();
        }

        // Hapus error (opsional jika belum terhapus)
        $('#add-mengetahui-form .invalid-feedback').remove();
        $('#add-mengetahui-form .is-invalid').removeClass('is-invalid');
        $('#add-mengetahui-form .alert-danger').remove();
    }

    $('#btn-add-mengetahui').on('click', function(){
        const mode = $(this).data('mode');
        if (mode === "add") {
            addMengetahuiForm.slideDown();
            $(this).html(`<i class="fa fa-minus"></i>`);
            $(this).data('mode', 'hide');
        } else {
            addMengetahuiForm.slideUp();
            $(this).html(`<i class="fa fa-plus"></i>`);
            $(this).data('mode', 'add');
        }
    });

    addMengetahuiForm.submit(function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        // Hapus error sebelumnya
        $('#add-mengetahui-form .invalid-feedback').remove();
        $('#add-mengetahui-form .is-invalid').removeClass('is-invalid');
        $('#add-mengetahui-form .alert-danger').remove();

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $.notify({ message: res.message }, { type: res.status });
                tableMengetahui.ajax.reload();
                resetAddMengetahuiForm();
            },
            error: function(res) {
                if (res.status === 422) {
                    const errors = res.responseJSON.errors;
                    let errorList = '';

                    $.each(errors, function(key, messages) {
                        const input = $(`[name="${key}"]`);

                        // Tambahkan kelas is-invalid untuk highlight merah (Bootstrap)
                        input.addClass('is-invalid');

                        // Tampilkan pesan error di bawah field
                        input.after(`<div class="invalid-feedback">${messages[0]}</div>`);

                        // Siapkan juga list error untuk alert umum
                        errorList += `<li>${messages[0]}</li>`;
                    });

                    // Tampilkan alert umum di atas form jika mau
                    $('#add-mengetahui-form').prepend(`
                        <div class="alert alert-danger">
                            <ul class="mb-0">${errorList}</ul>
                        </div>
                    `);
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

    $('#table-mengetahui').on('click', '.btn-destroy', function() {
        const id = $(this).data('id');
        let url = window.Laravel.mengetahuiDeleteUrl.replace("__ID__", id);
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
                $.ajax({
                    url: url,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    data: {
                        kontrakId: window.Laravel.kontrakId,
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
    });

    $('#table-mengetahui').on('click', '.editable', function () {
        const span = $(this);

        const id = span.data('id');
        const name = span.data('name');
        const value = span.data('value');

        if (span.hasClass('editing')) return;
        span.addClass('editing');

        if (span.hasClass('editable')) {
            const input = $('<input type="text" class="form-control form-control-sm" />').val(value);
            span.empty().append(input);
            input.focus().on('blur', function () {
                saveEdit(id, name, input.val(), span);
            }).on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Mencegah form submit jika ada
                    input.off('blur'); // Hindari pemanggilan ganda
                    saveEdit(id, name, input.val(), span);
                }
            });
        }
    });

    function saveEdit(id, name, value, span) {
        const mengetahuiInlineEditUrl = window.Laravel.mengetahuiInlineEditUrl.replace('__ID__', id)
        $.ajax({
            url: mengetahuiInlineEditUrl,
            type: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { name: name, value: value, kontrakId: window.Laravel.kontrakId, },
            success: function (res) {
                span.removeClass('editing');

                const safeValue = value && value.trim() !== '' ? value : '&nbsp;&nbsp;&nbsp;&nbsp;';
                span.html(safeValue);
                span.data('value', value);

                $.notify({ message: res.message }, { type: res.status });
            },
            error: function () {
                span.removeClass('editing');
                alert('Gagal menyimpan perubahan.');
            }
        });
    }
})
