$(document).ready(function() {
// --------------------
// Tabel Data SO
// --------------------
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const initializeDataTableSO = () => {
        return $('#table-so').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: window.Laravel.dataSOUrl,
                type: 'GET',
                data: {
                    kontrak_id: window.Laravel.kontrakId,
                }
            },
            columns: [
                {data: "DT_RowIndex"},
                {
                    data: "tgl_raw",
                    render: function(data, type, row) {
                        return row.tgl; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "nomor_raw",
                    render: function(data, type, row) {
                        return row.nomor; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "qty_raw",
                    render: function(data, type, row) {
                        return row.qty; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "sisa_raw",
                    render: function(data, type, row) {
                        return row.sisa; // tampilkan HTML inline-edit
                    }
                },
                {data: "action", orderable: false, searchable: false},
            ],
            scrollResize: true,
            scrollY: 300,
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            searching: true,
            dom: 'lrtip',
            ordering: true,
            info: false,
            initComplete: function () {
                this.api().columns([1,2,3,4]).every(function () {
                    var column = this;
                    var select = $('<select class="form-select"><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function (d, j) {
                        if (d) select.append('<option value="' + d + '">' + d + '</option>');
                    });
                });
            },
        });
    }

    const tableSO = initializeDataTableSO();

    const addDataSOForm = $('#add-data-so-form');

    function resetAddDataSOForm() {
        const form = $('#add-data-so-form')[0];

        // Reset form standar (input, select, dll)
        form.reset();

        // Reset AutoNumeric
        if (AutoNumeric.getAutoNumericElement('#add-qty-so')) {
            AutoNumeric.getAutoNumericElement('#add-qty-so').clear();
        }
        if (AutoNumeric.getAutoNumericElement('#add-sisa-so')) {
            AutoNumeric.getAutoNumericElement('#add-sisa-so').clear();
        }

        // Reset flatpickr (jika pakai Flatpickr)
        if ($('#add-tgl-so')[0]._flatpickr) {
            $('#add-tgl-so')[0]._flatpickr.clear();
        }

        // Hapus error (opsional jika belum terhapus)
        $('#add-data-so-form .invalid-feedback').remove();
        $('#add-data-so-form .is-invalid').removeClass('is-invalid');
        $('#add-data-so-form .alert-danger').remove();
    }

    $('#btn-add-data-so').on('click', function(){
        const mode = $(this).data('mode');
        if (mode === "add") {
            addDataSOForm.slideDown();
            $(this).html(`<i class="fa fa-minus"></i>`);
            $(this).data('mode', 'hide');
        } else {
            addDataSOForm.slideUp();
            $(this).html(`<i class="fa fa-plus"></i>`);
            $(this).data('mode', 'add');
        }
    });

    addDataSOForm.submit(function(e) {
        e.preventDefault();
        let rawQty = $('#add-qty-so').val().replace(/\./g, '');
        $('#add-qty-so').val(rawQty);
        let rawSisa = $('#add-sisa-so').val().replace(/\./g, '');
        $('#add-sisa-so').val(rawSisa);
        const form = this;
        const formData = new FormData(form);

        // Hapus error sebelumnya
        $('#add-data-so-form .invalid-feedback').remove();
        $('#add-data-so-form .is-invalid').removeClass('is-invalid');
        $('#add-data-so-form .alert-danger').remove();

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $.notify({ message: res.message }, { type: res.status });
                tableSO.ajax.reload();
                // $('#rekapKontrakPengiriman').html(res.rekap_html);
                resetAddDataSOForm();
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
                    $('#add-data-so-form').prepend(`
                        <div class="alert alert-danger">
                            <ul class="mb-0">${errorList}</ul>
                        </div>
                    `);
                } else {
                    swal("Error", "Terjadi kesalahan pada server.", {
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

    $('#table-so').on('click', '.btn-destroy', function() {
        const id = $(this).data('id');
        let url = window.Laravel.dataSODeleteUrl.replace("__ID__", id);
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
                            tableSO.ajax.reload();
                            // $('#rekapKontrakPengiriman').html(res.rekap_html);
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

    $('#table-so').on('click', '.editable, .editable-date', function () {
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

        if (span.hasClass('editable-date')) {
            const input = $('<input type="text" class="form-control form-control-sm date-picker" />');
            span.empty().append(input);

            input.flatpickr({
                dateFormat: "d/m/Y",
                defaultDate: value,
                onChange: function(selectedDates, dateStr) {
                    if (dateStr) {
                        saveEdit(id, name, dateStr, span);
                    }
                },
                onClose: function() {
                    span.removeClass('editing');
                    span.text(value);
                }
            });

            input.focus();
        }
    });

    function saveEdit(id, name, value, span) {
        const dataSOInlineEditUrl = window.Laravel.dataSOInlineEditUrl.replace('__ID__', id)
        $.ajax({
            url: dataSOInlineEditUrl,
            type: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { [name]: value, kontrakId: window.Laravel.kontrakId, },
            success: function (res) {
                span.removeClass('editing');

                if (name === 'qty' || name === 'sisa') {
                    const safeValue = value && value.trim() !== 0 ? value : '0';
                    span.text(safeValue);
                    span.data('value', value);
                    if (AutoNumeric.getAutoNumericElement(span[0])) {
                        AutoNumeric.getAutoNumericElement(span[0]).remove();
                    }
                    new AutoNumeric(span[0], {
                        decimalPlaces: 0,
                        digitGroupSeparator: '.',
                        decimalCharacter: ','
                    }).set(value);
                } else {
                    const safeValue = value && value.trim() !== '' ? value : '&nbsp;&nbsp;&nbsp;&nbsp;';
                    span.html(safeValue);
                    span.data('value', value);
                }

                $.notify({ message: res.message }, { type: res.status });
                // if (res.updateRekapData) {
                //     $('#rekapKontrakPengiriman').html(res.rekap_html);
                // }
            },
            error: function () {
                span.removeClass('editing');
                alert('Gagal menyimpan perubahan.');
            }
        });
    }
})
