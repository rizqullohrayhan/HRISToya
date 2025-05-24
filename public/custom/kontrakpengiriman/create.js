$(document).ready(function() {
    $(".flatpicker").flatpickr({
        dateFormat: "Y-m-d", // yang dikirim ke server
        altInput: true,
        altFormat: "d/m/Y", // yang dilihat user
    });

    // Inisialisasi AutoNumeric
    $(".input-number").each(function() {
        new AutoNumeric(this, {
            decimalCharacter: ",",
            digitGroupSeparator: ".",
            decimalPlaces: 0,
            unformatOnSubmit: true,
        });
    });

    $("#add-form").submit(function(e) {
        $("#btn-submit").prop("disabled", true);
        $("#btn-submit").html("loading...");
    });

    let rowIndexKebun = $(".row-kebun").length - 1;

    function updateTotalKuantitas() {
        let total = 0;
        $(".kontrak-kebun").each(function() {
            const anElement = AutoNumeric.getAutoNumericElement(this);
            if (anElement) {
                total += anElement.getNumber();
            }
        });
        const kuantitasAn = AutoNumeric.getAutoNumericElement("#kuantitas");
        if (kuantitasAn) {
            kuantitasAn.set(total);
        }
    }

    updateTotalKuantitas();

    $(document).on("input", ".kontrak-kebun", function() {
        updateTotalKuantitas();
    });

    $(".btn-hapus-kebun").on("click", function() {
        if ($(".row-kebun").length > 1) {
            $(this).closest(".row-kebun").remove();
            updateTotalKuantitas();
        } else {
            alert("Minimal harus ada satu baris.");
        }
    });

    $(".btn-add-kebun").on("click", function(event) {
        event.preventDefault();
        rowIndexKebun++;
        let rowBaru = $(".row-kebun").first().clone();

        rowBaru.find("input").each(function() {
            let name = $(this).attr("name");
            let id = $(this).attr("id");
            if (name) {
                $(this).attr("name", name.replace(/\d+/, rowIndexKebun));
            }
            if (id) {
                $(this).attr("id", id.replace(/\d+/, rowIndexKebun));
            }
            $(this).val("");
        });

        rowBaru.find('.btn-hapus-kebun').on("click", function() {
            if ($(".row-kebun").length > 1) {
                $(this).closest(".row-kebun").remove();
                updateTotalKuantitas();
            } else {
                alert("Minimal harus ada satu baris.");
            }
        });

        // Hapus AutoNumeric lama kalau ada, lalu inisialisasi ulang
        rowBaru.find(".input-number").each(function() {
            AutoNumeric.getAutoNumericElement(this)?.remove();
            new AutoNumeric(this, {
                decimalCharacter: ",",
                digitGroupSeparator: ".",
                decimalPlaces: 0,
                unformatOnSubmit: true,
            });
        });

        $("#tabel-kebun").append(rowBaru);
    });
});
