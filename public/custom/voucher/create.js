$(document).ready(function() {
    $('.select2').select2({width: '100%'});

    // Fungsi untuk inisialisasi datepicker
    function initDatepicker() {
        $(".input-date").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
        });
    }

    // Panggil datepicker untuk input yang sudah ada
    initDatepicker();

    // Inisialisasi AutoNumeric pada input dengan class 'input-number'
    new AutoNumeric(".input-number", {
        decimalCharacter: ",", // Gunakan koma untuk desimal
        digitGroupSeparator: ".", // Gunakan titik untuk ribuan
        decimalPlaces: 2, // Tampilkan 2 angka desimal
        unformatOnSubmit: true, // Saat dikirim ke server, format dikonversi ke angka biasa (10000.00)
    });

    $("#bank_code_id").on('change', function () {
        $(".kode_bank").val($(this).val()).trigger('change');
    })

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

    function inisialisasiSelect2Detail($element) {
        $element.select2({
            templateResult: function (data) {
                // Menampilkan "code - name" di dropdown
                if (!data.id) return data.text;
                return $(data.element).data('code') + ' - ' + data.text;
            },
            templateSelection: function (data) {
                // Menampilkan hanya "code" setelah opsi dipilih
                if (!data.id) return data.text;
                return $(data.element).data('code');
            },
            dropdownAutoWidth: true,
            width: '100%',
        });
    }

    function onChangeDetailPerkiraanRekanan($element, idFieldName) {
        $element.on('change', function() {
            let selectedOption = $(this).find('option:selected');  // Mendapatkan option yang dipilih
            let nameValue = selectedOption.data('name');  // Ambil nilai dari atribut data-name

            let index = $(this).attr('id').match(/\d+/)[0];  // Mendapatkan angka index dari id
            $('#details\\[' + index + '\\]\\[' + idFieldName + '\\]').val(nameValue);  // Set nilai input terkait
        });
    }

    inisialisasiSelect2Detail($('.kode_bank'));
    inisialisasiSelect2Detail($('.kode_perkiraan'));
    inisialisasiSelect2Detail($('.currency'));
    inisialisasiSelect2Detail($('.rekanan'));

    onChangeDetailPerkiraanRekanan($('.kode_perkiraan'), 'name');
    onChangeDetailPerkiraanRekanan($('.rekanan'), 'name_rekan');

    $(".btn-add-detail").on("click", function (event) {
        event.preventDefault(); // Mencegah form submit saat tombol ditekan

        rowIndex++; // Naikkan indeks untuk elemen baru
        let optKodeBank = $("#tabel-detail tr:first").find(".kode_bank").html();
        let optKodePerkiraan = $("#tabel-detail tr:first").find(".kode_perkiraan").html();
        let optMU = $("#tabel-detail tr:first").find(".currency").html();
        let optRekanan = $("#tabel-detail tr:first").find(".rekanan").html();
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

        rowBaru.find('.td_kode_bank').html(`
            <select name="details[${rowIndex}][bank_code_id]" class="kode_bank select_detail" id="details[${rowIndex}][bank_code_id]">
                ${optKodeBank}
            </select>
        `);
        rowBaru.find('.td_kode_perkiraan').html(`
            <select name="details[${rowIndex}][code]" class="kode_perkiraan select_detail" id="details[${rowIndex}][code]">
                ${optKodePerkiraan}
            </select>
        `);
        rowBaru.find('.td_currency').html(`
            <select name="details[${rowIndex}][currency_id]" class="currency" id="details[${rowIndex}][currency_id]">
                ${optMU}
            </select>
        `);
        rowBaru.find('.td_rekanan').html(`
            <select name="details[${rowIndex}][rekan_id]" class="rekanan" id="details[${rowIndex}][rekan_id]">
                ${optRekanan}
            </select>
        `);

        // Tambahkan baris baru ke tabel
        $("#tabel-detail").append(rowBaru);

        inisialisasiSelect2Detail($('.kode_bank'));
        inisialisasiSelect2Detail($('.kode_perkiraan'));
        inisialisasiSelect2Detail($('.currency'));
        inisialisasiSelect2Detail($('.rekanan'));
        let selectedBank = $("#bank_code_id").val();
        rowBaru.find('.kode_bank').val(selectedBank).trigger('change');
        rowBaru.find('.kode_perkiraan').val('').trigger('change');
        rowBaru.find('.rekanan').val('').trigger('change');
        onChangeDetailPerkiraanRekanan(rowBaru.find('.kode_perkiraan'), 'name');
        onChangeDetailPerkiraanRekanan(rowBaru.find('.rekanan'), 'name_rekan');

        // Inisialisasi AutoNumeric untuk input number di baris baru
        new AutoNumeric(rowBaru.find(".input-number")[0], {
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 2,
            unformatOnSubmit: true,
        });

        rowBaru.find(".input-date").removeClass("hasDatepicker").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
        });
    });
});
