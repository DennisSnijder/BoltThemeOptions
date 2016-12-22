jQuery(document).ready(function() {


    $('.option-wrapper > .color-picker').each(function() {
        $(this).spectrum({
            preferredFormat: "hex",
            showInput: true
        });
    });

    $('.option-wrapper > input[type=date]').each(function() {
        $(this).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });

});