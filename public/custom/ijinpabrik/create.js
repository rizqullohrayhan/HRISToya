$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
    });

    $(".flatpicker").flatpickr({
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        time_24hr: true,
    });

    $("#add-form").submit(function (e) {
        $("#btn-submit").prop("disabled", true);
        $("#btn-submit").html("loading...");
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
                $('.otorisasi-disetujui').prop('disabled', false);
            } else if (target === 'disetujui') {
                $('.otorisasi-dibuat').prop('disabled', true);
                $('.otorisasi-diterima').prop('disabled', false);
            } else if (target === 'diterima') {
                $('.otorisasi-disetujui').prop('disabled', true);
                $('.otorisasi-mengetahui').prop('disabled', false);
            } else if (target === 'mengetahui') {
                $('.otorisasi-diterima').prop('disabled', true);
            }
        } else {
            // Kosongkan kolom
            otorisasiCell.html(`
                <input type="hidden" name="${target}_by" id="${target}_by" value="">
                <input type="hidden" name="${target}_at" id="${target}_at" value="">
            `);

            // Ubah teks tombol dan kelas
            $(this).removeClass('hapus-mode').addClass('otorisasi-mode').text('Otorisasi');

            // Atur disable tombol pasangan
            if (target === 'dibuat') {
                $('.otorisasi-disetujui').prop('disabled', true);
            } else if (target === 'disetujui') {
                $('.otorisasi-dibuat').prop('disabled', false);
                $('.otorisasi-diterima').prop('disabled', true);
            } else if (target === 'diterima') {
                $('.otorisasi-disetujui').prop('disabled', false);
                $('.otorisasi-mengetahui').prop('disabled', true);
            } else if (target === 'mengetahui') {
                $('.otorisasi-diterima').prop('disabled', false);
            }
        }
    });
});
