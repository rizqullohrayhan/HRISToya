$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
    });

    // Fungsi untuk inisialisasi datepicker
    function initDatepicker() {
        $(".input-date").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
        });
    }

    $(".flatpicker").flatpickr({
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        time_24hr: true,
    });

    // Panggil datepicker untuk input yang sudah ada
    initDatepicker();

    $("#add-form").submit(function (e) {
        $("#btn-submit").prop("disabled", true);
        $("#btn-submit").html("loading...");
    });

    let rowIndex = $(".row-detail").length - 1; // Hitung jumlah row yang ada saat ini

    // Event listener untuk tombol hapus pertama (karena sudah ada di HTML)
    $(".btn-hapus").on("click", function () {
        if ($(".row-detail").length > 1) {
            $(this).closest(".row-detail").remove();
        } else {
            alert("Minimal harus ada satu baris.");
        }
    });

    $(".btn-add-detail").on("click", function (event) {
        event.preventDefault(); // Mencegah form submit saat tombol ditekan

        rowIndex++; // Naikkan indeks untuk elemen baru
        let rowBaru = $(".row-detail").first().clone(); // Duplikat baris pertama

        // Perbarui atribut name dan id sesuai dengan indeks baru
        rowBaru.find("input").each(function () {
            let name = $(this).attr("name");
            let id = $(this).attr("id");

            if (name) {
                let newName = name.replace(/\d+/, rowIndex); // Ubah indeks di name
                $(this).attr("name", newName);
            }
            if (id) {
                let newId = id.replace(/\d+/, rowIndex); // Ubah indeks di id
                $(this).attr("id", newId);
            }

            $(this).val(""); // Kosongkan nilai input
        });

        // Tambahkan event listener untuk tombol hapus di baris baru
        rowBaru.find(".btn-hapus").on("click", function () {
            if ($(".row-detail").length > 1) {
                $(this).closest(".row-detail").remove();
            } else {
                alert("Minimal harus ada satu baris.");
            }
        });

        // Tambahkan baris baru ke tabel
        $("#tabel-detail").append(rowBaru);
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
