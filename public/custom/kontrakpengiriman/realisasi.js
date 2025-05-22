$(document).ready(function() {
// --------------------
// Tabel Detail Pengiriman
// --------------------
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function inisialisasiFilterDropdown(api) {
        api.columns([1,2,5,8]).every(function () {
            let column = this;
            let select = $('<select class="form-select"><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function () {
                    let val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            column.data().unique().sort().each(function (d, j) {
                if (d) select.append('<option value="' + d + '">' + d + '</option>')
            });
        });
    }

    // Inisialisasi DataTables untuk tabel detail pengiriman
    const initializeDataTableDetail = () => {
        return $('#table-detail').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: window.Laravel.detailUrl,
                type: 'GET',
                data: {
                    kontrak_id: window.Laravel.kontrakId,
                }
            },
            columns: [
                {data: "DT_RowIndex"},
                {
                    data: "tgl",
                    render: function(data, type, row) {
                        return row.tgl_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "nopol",
                    render: function(data, type, row) {
                        return row.nopol_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "no_sj",
                    render: function(data, type, row) {
                        return row.no_sj_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "no_so_pkt",
                    render: function(data, type, row) {
                        return row.no_so_pkt_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "vendor",
                    render: function(data, type, row) {
                        return row.vendor_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "kirim",
                    render: function(data, type, row) {
                        return row.kirim_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "terima",
                    render: function(data, type, row) {
                        return row.terima_span; // tampilkan HTML inline-edit
                    }
                },
                {
                    data: "kebun",
                    render: function(data, type, row) {
                        return row.kebun_span; // tampilkan HTML inline-edit
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
                // Tambahkan filter dropdown untuk kolom tertentu
                inisialisasiFilterDropdown(this.api());
            },
        });
    }

    let tableDetail = initializeDataTableDetail();

    const addDetailForm = $('#add-detail-form');

    // Reset form tambah detail
    function resetAddDetailForm() {
        const form = $('#add-detail-form')[0];
        // Reset form standar (input, select, dll)
        form.reset();
        // Reset Select2
        $('#add-kebun-detail').val('').trigger('change');

        // Reset AutoNumeric
        if (AutoNumeric.getAutoNumericElement('#add-kirim-detail')) {
            AutoNumeric.getAutoNumericElement('#add-kirim-detail').clear();
        }
        if (AutoNumeric.getAutoNumericElement('#add-terima-detail')) {
            AutoNumeric.getAutoNumericElement('#add-terima-detail').clear();
        }

        // Reset flatpickr (jika pakai Flatpickr)
        if ($('#add-tgl-detail')[0]._flatpickr) {
            $('#add-tgl-detail')[0]._flatpickr.clear();
        }

        // Hapus error (opsional jika belum terhapus)
        $('#add-detail-form .invalid-feedback').remove();
        $('#add-detail-form .is-invalid').removeClass('is-invalid');
        $('#add-detail-form .alert-danger').remove();
    }

    // Menampilkan dan menyembunyikan form detail realisasi pengiriman
    $('#btn-add-detail').on('click', function(){
        const mode = $(this).data('mode');
        if (mode === "add") {
            addDetailForm.slideDown();
            $(this).html(`<i class="fa fa-minus"></i>`);
            $(this).data('mode', 'hide');
        } else {
            addDetailForm.slideUp();
            $(this).html(`<i class="fa fa-plus"></i>`);
            $(this).data('mode', 'add');
        }
    });

    addDetailForm.submit(function(e) {
        e.preventDefault();
        let rawKirim = $('#add-kirim-detail').val().replace(/\./g, '');
        $('#add-kirim-detail').val(rawKirim);
        let rawTerima = $('#add-terima-detail').val().replace(/\./g, '');
        $('#add-terima-detail').val(rawTerima);
        const form = this;
        const formData = new FormData(form);

        // Hapus error sebelumnya
        $('#add-detail-form .invalid-feedback').remove();
        $('#add-detail-form .is-invalid').removeClass('is-invalid');
        $('#add-detail-form .alert-danger').remove();

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $.notify({ message: res.message }, { type: res.status });
                tableDetail.ajax.reload(() => {
                    inisialisasiFilterDropdown(tableDetail);
                });
                $('#rekapKontrakPengiriman').html(res.rekap_html);
                resetAddDetailForm();
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

    $('#table-detail').on('click', '.btn-destroy', function() {
        const id = $(this).data('id');
        let url = window.Laravel.detailDeleteUrl.replace("__ID__", id);
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
                            tableDetail.ajax.reload(() => {
                                inisialisasiFilterDropdown(tableDetail);
                            });
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

    function buildSelect(currentValue) {
        let html = '<select class="form-select form-select-sm select-kebun">';
        $.each(window.kebunList, function (id, text) {
            html += `<option value="${id}" ${id == currentValue ? 'selected' : ''}>${text}</option>`;
        });
        html += '</select>';
        return html;
    }

    // Inline editing pada kolom editable
    $('#table-detail').on('click', '.editable, .editable-date, .editable-select', function () {
        const span = $(this);

        const id = span.data('id');
        const name = span.data('name');
        const value = span.data('value');

        if (span.hasClass('editing')) return;
        span.addClass('editing');

        // Input teks biasa
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

        // Input tanggal
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

        // Dropdown kebun
        if (span.hasClass('editable-select')) {
            const select = $(buildSelect(value));
            span.empty().append(select);
            select.focus().on('blur change', function () {
                saveEdit(id, name, select.val(), span);
            });
        }
    });

    function saveEdit(id, name, value, span) {
        const detailInlineEditUrl = window.Laravel.detailInlineEditUrl.replace('__ID__', id)
        $.ajax({
            url: detailInlineEditUrl,
            type: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { [name]: value, kontrakId: window.Laravel.kontrakId, },
            success: function (res) {
                span.removeClass('editing');

                if (name === 'rekap_kebun_pengiriman_id') {
                    span.text(window.kebunList[value]);
                    span.data('value', value);
                } else if (name === 'kirim' || name === 'terima') {
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
})
