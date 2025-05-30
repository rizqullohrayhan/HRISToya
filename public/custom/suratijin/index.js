const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// let dateDropdown = document.getElementById('date-dropdown');

// let currentYear = new Date().getFullYear();
// let tahun = currentYear;
// let earliestYear = 2020;

// while (currentYear >= earliestYear) {
//     let dateOption = document.createElement('option');
//     dateOption.text = currentYear;
//     dateOption.value = currentYear;
//     dateDropdown.add(dateOption);
//     currentYear -= 1;
// }
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

let start = $( "#start-date" ).val();
let end = $( "#end-date" ).val();
const urlData = window.Laravel.dataUrl;

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
            {data: "no_surat"},
            {data: "tanggal"},
            {data: "jam"},
            {data: "user.name"},
            // {data: "action", visible: false},
        ],
        order: [[1, "desc"]],
    });
}

let table = initializeDataTable(start, end);

// $('#date-dropdown').on('change', function () {
//     tahun = $(this).val();
//     table = initializeDataTable(start, end);
// })

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
