$(document).ready(function(){
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// --------------------
// Tabel Rencana Pengiriman
// --------------------

    function inisialisasiFilterDropdown(api) {
        api.columns([1,2,3,4]).every(function () {
            const column = this;
            const select = $('<select class="form-select"><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function () {
                    const val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            column.data().unique().sort().each(function (d) {
                if (d) select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    }

    const initializeDataTableRencana = () => {
        return $('#table-rencana').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: window.Laravel.rencanaUrl,
                type: 'GET',
                data: {
                    kontrak_id: window.Laravel.kontrakId,
                }
            },
            columns: [
                {data: "DT_RowIndex"},
                {data: "vendor"},
                {
                    data: "kebun_raw",
                    render: function(data, type, row) {
                        return row.kebun; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "tgl_raw",
                    render: function(data, type, row) {
                        return row.tgl; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "nopol_raw",
                    render: function(data, type, row) {
                        return row.nopol; // tampilkan HTML inline-edit
                    }
                },
                {data: "qty"},
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
                // Tambahkan filter dropdown untuk kolom tertentu
                inisialisasiFilterDropdown(this.api());
            },
        });
    }

    const tableRencana = initializeDataTableRencana();

    $('#table-rencana').on('draw.dt', function () {
        $('.format-number').each(function () {
            const value = $(this).data('value') || 0;
            if (AutoNumeric.getAutoNumericElement(this)) {
                AutoNumeric.getAutoNumericElement(this).remove();
            }
            new AutoNumeric(this, {
                decimalPlaces: 0,
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                unformatOnSubmit: true
            }).set(value);
        });
    });

    const addRencanaForm = $('#add-rencana-form');

    function resetAddRencanaForm() {
        const form = $('#add-rencana-form')[0];

        // Reset form standar (input, select, dll)
        form.reset();

        // Reset Select2
        $('#add-kebun-rencana').val('').trigger('change');

        // Reset AutoNumeric
        if (AutoNumeric.getAutoNumericElement('#add-qty-rencana')) {
            AutoNumeric.getAutoNumericElement('#add-qty-rencana').clear();
        }

        // Reset flatpickr (jika pakai Flatpickr)
        if ($('#add-tgl-rencana')[0]._flatpickr) {
            $('#add-tgl-rencana')[0]._flatpickr.clear();
        }

        // Hapus error (opsional jika belum terhapus)
        $('#add-rencana-form .invalid-feedback').remove();
        $('#add-rencana-form .is-invalid').removeClass('is-invalid');
        $('#add-rencana-form .alert-danger').remove();
    }

    // Menampilkan dan menyembunyikan form rencana pengiriman
    $('#btn-add-rencana').on('click', function(){
        const mode = $(this).data('mode');
        if (mode === "add") {
            // addRencanaForm.css('display','block');
            addRencanaForm.slideDown();
            $(this).html(`<i class="fa fa-minus"></i>`);
            $(this).data('mode', 'hide');
        } else {
            // addRencanaForm.css('display','none');
            addRencanaForm.slideUp();
            $(this).html(`<i class="fa fa-plus"></i>`);
            $(this).data('mode', 'add');
        }
    });

    addRencanaForm.submit(function(e) {
        e.preventDefault();
        let rawQty = $('#add-qty-rencana').val().replace(/\./g, '');
        $('#add-qty-rencana').val(rawQty);
        const form = this;
        const formData = new FormData(form);

        // Hapus error sebelumnya
        $('#add-rencana-form .invalid-feedback').remove();
        $('#add-rencana-form .is-invalid').removeClass('is-invalid');
        $('#add-rencana-form .alert-danger').remove();

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $.notify({ message: res.message }, { type: res.status });
                tableRencana.ajax.reload(() => {
                    inisialisasiFilterDropdown(tableRencana);
                });
                $('#rekapKontrakPengiriman').html(res.rekap_html);
                resetAddRencanaForm();
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
                    $('#add-rencana-form').prepend(`
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

    $('#table-rencana').on('click', '.btn-destroy', function() {
        const id = $(this).data('id');
        let url = window.Laravel.rencanaDeleteUrl.replace("__ID__", id);
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
                            tableRencana.ajax.reload(() => {
                                inisialisasiFilterDropdown(tableDetail);
                            });
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

    function buildSelect(currentValue) {
        let html = '<select class="form-select form-select-sm select-kebun">';
        $.each(window.kebunList, function (id, text) {
            html += `<option value="${id}" ${id == currentValue ? 'selected' : ''}>${text}</option>`;
        });
        html += '</select>';
        return html;
    }

    $('#table-rencana').on('click', '.editable, .editable-date, .editable-select', function () {
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

        if (span.hasClass('editable-select')) {
            const select = $(buildSelect(value));
            span.empty().append(select);
            select.focus().on('blur change', function () {
                saveEdit(id, name, select.val(), span);
            });
        }
    });

    function saveEdit(id, name, value, span) {
        const rencanaInlineEditUrl = window.Laravel.rencanaInlineEditUrl.replace('__ID__', id)
        $.ajax({
            url: rencanaInlineEditUrl,
            type: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { [name]: value, kontrakId: window.Laravel.kontrakId, },
            success: function (res) {
                span.removeClass('editing');

                if (name === 'rekap_kebun_pengiriman_id') {
                    span.text(window.kebunList[value]);
                    span.data('value', value);
                } else if (name === 'qty') {
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
                if (res.updateRekapData) {
                    $('#rekapKontrakPengiriman').html(res.rekap_html);
                }
            },
            error: function () {
                span.removeClass('editing');
                alert('Gagal menyimpan perubahan.');
            }
        });
    }
});
