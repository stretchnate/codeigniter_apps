$(function() {
//    $('.tool-tip').tooltip({
//        track: true,
//        delay: 1,
//        showURL: false,
//        opacity: .85,
//        fixPNG: true,
//        top: 15,
//        left: 5
//    });

    $('.money').blur(function() {
        //remove all comma's and dollar signs
        var regex    = new RegExp(/[\$,]/g);
        var re_2_dec = new RegExp(/(\.\d{2})\d+/);
        var amount   = $(this).val();
        var dec      = re_2_dec.exec(amount);

        if(dec) {
            amount = amount.replace(re_2_dec, dec[1]);
        }

        amount = amount.replace(regex, '');

        $(this).val(amount);
    });

    $('.date.glyphicon').click(function() {
        $(this).parent().sibling('input.form-control').datepicker();
    });
});