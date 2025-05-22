$(document).ready(function() {
    // Inisialisasi AutoNumeric
    $(".format-number").each(function() {
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

    $('.select2').select2({
        width: '100%',
    });

    $(".flatpicker").flatpickr({
        dateFormat: "d/m/Y",
    });
});
