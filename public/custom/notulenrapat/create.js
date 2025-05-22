function initializeTrumbowyg(context) {
    // context: elemen (optional) tempat kita ingin cari textarea .editor
    const $editors = context ? $(context).find('textarea.editor') : $('textarea.editor');

    $editors.each(function () {
        if (!$(this).next('.trumbowyg-box').length) { // Cegah inisialisasi ganda
            $(this).trumbowyg({
                btns: [
                    ['fontsize'],
                    ['strong', 'em', 'del'],
                    ['unorderedList', 'orderedList'],
                ],
                plugins: {
                    fontsize: {
                        sizeList: [
                            '12pt',
                            '14pt',
                        ],
                        allowCustomSize: true,
                    }
                }
            });
        }
    });
}

function initializeSummernote(context) {
    // context: elemen (optional) tempat kita ingin cari textarea .editor
    const $editors = context ? $(context).find('textarea.editor') : $('textarea.editor');

    $editors.each(function () {
        if (!$(this).next('.note-editor').length) { // Cegah inisialisasi ganda
            $(this).summernote({
                toolbar: [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                // fontsize: ['12pt', '14pt'], // Daftar ukuran font khusus
                height: 100
            });
        }
    });
}


function initializeFlatpickrDetail(context) {
    $(context).flatpickr({
        dateFormat: "d/m/Y",
    });
}

$(document).ready(function() {
    const optUserHadir = $("#tabel-hadir tr:first").find(".select_hadir").html();
    // Inisialisasi awal pada semua textarea .editor
    // initializeTrumbowyg();
    initializeSummernote();

    $(".flatpicker").flatpickr({
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        time_24hr: true,
    });

    initializeFlatpickrDetail(".flatpicker-detail");
    $(".select_hadir").select2({
        placeholder: "Pilih User",
        allowClear: true,
        width: '100%',
    });

    $("#add-form").submit(function (e) {
        $("#btn-submit").prop("disabled", true);
        $("#btn-submit").html("loading...");
    });

    let rowIndexDetail = $(".row-detail").length - 1; // Hitung jumlah row yang ada saat ini
    let rowIndexHadir = $(".row-hadir").length - 1; // Hitung jumlah row yang ada saat ini

    // Event listener untuk tombol hapus pertama (karena sudah ada di HTML)
    $(".btn-hapus-detail").on("click", function () {
        if ($(".row-detail").length > 1) {
            $(this).closest(".row-detail").remove();
        } else {
            alert("Minimal harus ada satu baris.");
        }
    });
    $(".btn-hapus-hadir").on("click", function () {
        if ($(".row-hadir").length > 1) {
            $(this).closest(".row-hadir").remove();
        } else {
            alert("Minimal harus ada satu baris.");
        }
    });

    $(".btn-add-uraian").on("click", function (event) {
        event.preventDefault();

        rowIndexDetail++;

        let rowBaru = $(".row-detail").first().clone();

        rowBaru.find("input, textarea").each(function () {
            let name = $(this).attr("name");
            let id = $(this).attr("id");

            if (name) {
                $(this).attr("name", name.replace(/\d+/, rowIndexDetail));
            }
            if (id) {
                $(this).attr("id", id.replace(/\d+/, rowIndexDetail));
            }

            $(this).val("");
        });

        rowBaru.find('.form-uraian').html(`
            <label for="detail_${ rowIndexDetail }_uraian" class="form-label">Uraian</label>
            <textarea class="form-control editor" name="detail[${ rowIndexDetail }][uraian]" id="detail_${ rowIndexDetail }_uraian"></textarea>
        `);
        rowBaru.find('.form-action').html(`
            <label for="detail_${ rowIndexDetail }_action" class="form-label">Action</label>
            <textarea class="form-control editor" name="detail[${ rowIndexDetail }][action]" id="detail_${ rowIndexDetail }_action"></textarea>
        `);
        // Inisialisasi summernote hanya pada editor di row baru
        initializeSummernote(rowBaru);

        rowBaru.find('.btn-hapus-detail').on("click", function () {
            if ($(".row-detail").length > 1) {
                $(this).closest(".row-detail").remove();
            } else {
                alert("Minimal harus ada satu baris.");
            }
        });

        $("#tabel-detail").append(rowBaru);


        initializeFlatpickrDetail(".flatpicker-detail");
    });

    $(".btn-add-hadir").on("click", function (event) {
        event.preventDefault();

        rowIndexHadir++;
        let HadirHTML = `
            <tr class="row-hadir">
                <td>
                    <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus-hadir">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
                <td class="td-hadir">
                    <select name="hadir[${rowIndexHadir}][user_id]" class="select_hadir" id="hadir_${rowIndexHadir}_user_id">
                        ${optUserHadir}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="hadir[${rowIndexHadir}][nama]" id="hadir_${rowIndexHadir}_nama">
                </td>
            </tr>
        `;

        let $rowBaru = $(HadirHTML);
        $rowBaru.find('.select_hadir').select2({
            width: '100%',
            placeholder: "Pilih User",
            allowClear: true,
        }).val("").trigger('change');
        $rowBaru.find('.btn-hapus-hadir').on('click', function () {
            if ($(".row-hadir").length > 1) {
                $(this).closest(".row-hadir").remove();
            } else {
                alert("Minimal harus ada satu baris.");
            }
        });
        $("#tabel-hadir").append($rowBaru);
    });

    $('.otorisasi-btn').on('click', function () {
        const userId = window.Laravel.userId;
        const name = window.Laravel.name;
        const team = window.Laravel.team;

        // Format waktu saat ini (Indonesia)
        const now = new Date().toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });

        // Ambil target kolom dari atribut data-target (misalnya 'dibuat', 'mengetahui')
        const target = $(this).data('target');
        const otorisasiCell = $(`#otorisasi-table tbody tr td.${target}`);

        if ($(this).hasClass('otorisasi-mode')) {
            const waktuLokal = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Jakarta' }).replace(' ', 'T');
            // Tambahkan tampilan nama user, waktu, dan team di dalam sel tabel (untuk visualisasi)
            otorisasiCell.html(`
                <div class="d-flex flex-column">
                    <span>${now}</span>
                    <span><strong>${name}</strong></span>
                    <span>${team}</span>
                    <input type="hidden" name="${target}_by" id="${target}_by" value="${userId}">
                    <input type="hidden" name="${target}_at" id="${target}_at" value="${waktuLokal}">
                </div>
            `);

            // Ubah teks tombol dan kelas
            $(this).removeClass('otorisasi-mode').addClass('hapus-mode').text('Hapus');

            // Atur disable tombol pasangan
            if (target === 'dibuat') {
                $('.otorisasi-diperiksa').prop('disabled', false);
            } else if (target === 'diperiksa') {
                $('.otorisasi-dibuat').prop('disabled', true);
                $('.otorisasi-disetujui').prop('disabled', false);
            } else if (target === 'disetujui') {
                $('.otorisasi-diperiksa').prop('disabled', true);
                $('.otorisasi-mengetahui').prop('disabled', false);
            } else if (target === 'mengetahui') {
                $('.otorisasi-disetujui').prop('disabled', true);
            }
        } else {
            // Kosongkan kolom
            otorisasiCell.html(`
                <input type="hidden" name="${target}_by" id="${target}_by" value="">
                <input type="hidden" name="${target}_at" id="${target}_at" value="">
            `);

            // Ubah teks tombol dan kelas
            $(this).removeClass('hapus-mode').addClass('otorisasi-mode').text('Otorisasi');

            // Atur disable tombol pasangan sesuai kondisi
            if (target === 'dibuat') {
                $('.otorisasi-diperiksa').prop('disabled', true);
            } else if (target === 'diperiksa') {
                $('.otorisasi-dibuat').prop('disabled', false);
                $('.otorisasi-disetujui').prop('disabled', true);
            } else if (target === 'disetujui') {
                $('.otorisasi-diperiksa').prop('disabled', false);
                $('.otorisasi-mengetahui').prop('disabled', true);
            } else if (target === 'mengetahui') {
                $('.otorisasi-disetujui').prop('disabled', false);
            }
        }
    });

});
