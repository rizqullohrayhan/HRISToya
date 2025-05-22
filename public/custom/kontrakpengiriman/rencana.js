$(document).ready(function(){
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modal = $('#modal-default');

// --------------------
// Tabel Rencana Pengiriman
// --------------------

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
                {data: "kebun"},
                {data: "tgl"},
                {data: "nopol"},
                {data: "qty"},
                {data: "action", orderable: false, searchable: false},
            ],
            scrollResize: true,
            scrollY: 300,
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            searching: false,
            ordering: false,
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
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            },
        });
    }

    const tableRencana = initializeDataTableRencana();

    const addRencanaForm = $('#add-rencana-form');

    function resetAddRencanaForm() {
        const form = $('#add-rencana-form')[0];

        // Reset form standar (input, select, dll)
        form.reset();

        // Reset Select2
        $('#rencana-kebun').val('').trigger('change');

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
                modal.modal('hide');
                $.notify({ message: res.message }, { type: res.status });
                tableRencana.ajax.reload();
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

    // $('#tambah-rencana').on('click', function() {
    //     $.ajax({
    //         url: window.Laravel.rencanaCreateUrl,
    //         data: {
    //             kontrakId: window.Laravel.kontrakId,
    //         },
    //         success: function(res) {
    //             modal.html(res).modal('show');

    //             modal.off('shown.bs.modal').on('shown.bs.modal', function() {
    //                 modal.find('.select2').select2({
    //                     dropdownParent: modal
    //                 });
    //                 modal.find('.input-number').each(function() {
    //                     if (AutoNumeric.getAutoNumericElement(this)) {
    //                         AutoNumeric.getAutoNumericElement(this).remove();
    //                     }
    //                     new AutoNumeric(this, {
    //                         decimalCharacter: ",",
    //                         digitGroupSeparator: ".",
    //                         decimalPlaces: 0,
    //                         unformatOnSubmit: true,
    //                     });
    //                 });
    //                 modal.find('.flatpicker').each(function() {
    //                     if (this._flatpickr) {
    //                         this._flatpickr.destroy();
    //                     }
    //                     this.flatpickr({
    //                         dateFormat: "d/m/Y",
    //                     });
    //                 });
    //             });

    //             modal.off('submit', '#form-action').on('submit', '#form-action', function(e) {
    //                 e.preventDefault();
    //                 let rawQty = $('#qty').val().replace(/\./g, '');
    //                 $('#qty').val(rawQty);
    //                 const form = this;
    //                 const formData = new FormData(form);

    //                 formData.append('kontrakId', window.Laravel.kontrakId);

    //                 // Hapus error sebelumnya
    //                 $('#form-action .invalid-feedback').remove();
    //                 $('#form-action .is-invalid').removeClass('is-invalid');
    //                 $('#form-action .alert-danger').remove();

    //                 $.ajax({
    //                     url: form.action,
    //                     type: form.method,
    //                     data: formData,
    //                     processData: false,
    //                     contentType: false,
    //                     success: function(res) {
    //                         modal.modal('hide');
    //                         $.notify({ message: res.message }, { type: res.status });
    //                         tableRencana.ajax.reload();
    //                         $('#rekapKontrakPengiriman').html(res.rekap_html);
    //                     },
    //                     error: function(res) {
    //                         if (res.status === 422) {
    //                             const errors = res.responseJSON.errors;
    //                             let errorList = '';

    //                             $.each(errors, function(key, messages) {
    //                                 const input = $(`[name="${key}"]`);

    //                                 // Tambahkan kelas is-invalid untuk highlight merah (Bootstrap)
    //                                 input.addClass('is-invalid');

    //                                 // Tampilkan pesan error di bawah field
    //                                 input.after(`<div class="invalid-feedback">${messages[0]}</div>`);

    //                                 // Siapkan juga list error untuk alert umum
    //                                 errorList += `<li>${messages[0]}</li>`;
    //                             });

    //                             // Tampilkan alert umum di atas form jika mau
    //                             $('#form-action').prepend(`
    //                                 <div class="alert alert-danger">
    //                                     <ul class="mb-0">${errorList}</ul>
    //                                 </div>
    //                             `);
    //                         } else {
    //                             swal("Error", "Terjadi kesalahan pada server.", {
    //                                 icon : "error",
    //                                 buttons: {
    //                                     confirm: {
    //                                         className : 'btn btn-danger'
    //                                     }
    //                                 },
    //                             });
    //                         }
    //                     }
    //                 });
    //             });
    //         }
    //     })
    // });

    $('#table-rencana').on('click', '.btn-rencana-edit', function() {
        const id = $(this).data('id');
        let url = window.Laravel.rencanaEditUrl.replace("__ID__", id);
        $.ajax({
            url: url,
            data: {
                kontrakId: window.Laravel.kontrakId,
            },
            success: function(res) {
                modal.html(res).modal('show');

                modal.off('shown.bs.modal').on('shown.bs.modal', function() {
                    modal.find('.select2').select2({
                        dropdownParent: modal
                    });
                    modal.find('.input-number').each(function() {
                        if (AutoNumeric.getAutoNumericElement(this)) {
                            AutoNumeric.getAutoNumericElement(this).remove();
                        }
                        new AutoNumeric(this, {
                            decimalCharacter: ",",
                            digitGroupSeparator: ".",
                            decimalPlaces: 0,
                            unformatOnSubmit: true,
                        });
                    });
                    modal.find('.flatpicker').each(function() {
                        if (this._flatpickr) {
                            this._flatpickr.destroy();
                        }
                        this.flatpickr({
                            dateFormat: "d/m/Y",
                        });
                    });
                });

                modal.off('submit', '#form-action').on('submit', '#form-action', function(e) {
                    e.preventDefault();
                    let rawQty = $('#qty').val().replace(/\./g, '');
                    $('#qty').val(rawQty);
                    const form = this;
                    const formData = new FormData(form);

                    formData.append('kontrakId', window.Laravel.kontrakId);

                    // Hapus error sebelumnya
                    $('#form-action .invalid-feedback').remove();
                    $('#form-action .is-invalid').removeClass('is-invalid');
                    $('#form-action .alert-danger').remove();

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            modal.modal('hide');
                            $.notify({
                                message: res.message
                            }, {
                                type: res.status
                            });
                            tableRencana.ajax.reload();
                            $('#rekapKontrakPengiriman').html(res.rekap_html);
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
                                $('#form-action').prepend(`
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
                });
            }
        })
    });

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
                            tableRencana.ajax.reload();
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

    // Event listener untuk elemen yang dapat diedit
    $('#table-rencana').on('click', '.editable, .editable-select, .editable-date', function() {
        const $this = $(this);
        const id = $this.data('id');
        const name = $this.data('name');
        const value = $this.data('value') || $this.text();

        // Buat input field untuk editing
        let inputField;
        if ($this.hasClass('editable-select')) {
            inputField = $('<select class="form-control"><option value="">Select</option></select>');
            // Tambahkan opsi ke select (misalnya dari data yang ada)
            // inputField.append('<option value="1">Option 1</option>');
            // inputField.append('<option value="2">Option 2</option>');
        } else if ($this.hasClass('editable-date')) {
            inputField = $('<input type="text" class="form-control flatpicker" value="' + value + '">');

        } else {
            inputField = $('<input type="text" class="form-control" value="' + value + '">');
        }

        // Ganti span dengan input field
        $this.replaceWith(inputField);

        // Fokus pada input field
        inputField.focus();

        // Event listener untuk menyimpan perubahan
        inputField.on('blur', function() {
            const newValue = $(this).val();
            $.ajax({
                url: '/rencana-pengiriman/' + id, // Ganti dengan URL yang sesuai
                type: 'PUT',
                data: {
                    name: name,
                    value: newValue,
                    _token: csrfToken // CSRF token
                },
                success: function(response) {
                    // Ganti input field dengan nilai baru
                    // const updatedSpan = $('<span class="editable" data-id="' + id + '" data-name="' + name + '">' + newValue + '</span>');
                    // inputField.replaceWith(updatedSpan);
                    tableRencana.ajax.reload();
                },
                error: function(xhr) {
                    // Tangani error
                    alert('Error updating data');
                }
            });
        });

        // Jika pengguna menekan Enter, simpan perubahan
        inputField.on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $(this).blur(); // Trigger blur event
            }
        });
    });
})
