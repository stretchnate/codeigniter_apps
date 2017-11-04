$(document).ready(function() {
    $("#refund").hide();

    /**
     * show/hide refund transaction id field
     */
    $("select[name=operation]").change(function() {
        if($(this).val() == 'refund') {
            $("#refund").show();
        } else {
            $("#refund").hide();
        }
    });

    /**
     * toggle refund value
     */
    $("#refund").focus(function() {
        if($(this).val() === 'Refund Transaction ID') {
            $(this).val('');
        }
    });

    /**
     * toggle refund value
     */
    $("#refund").blur(function() {
        if(!$(this).val()) {
            $(this).val('Refund Transaction ID');
        }
    });

    $("form[name=bookEditForm]").submit(function() {
        //remove any comma's in the amount
        $("input[name=amount]").val($("input[name=amount]").val().replace(/,/g, ''));
        $("form[name=bookEditForm]").validate();
        if( !$("form[name=bookEditForm]").valid()) {
            return false;
        }
    });


    $("select[name=accounts_select]").change(function() {
        window.location = "/book/getBookInfo/"+$(this).val()+"/";
    });

    $("tr.transaction input[type=checkbox]").change(function() {
        $.post("/book/transactionCleared/",{ transaction_id: $(this).val() },
            function(data) {
                if(!data.success) {
                    alert("there was a problem updating transaction status")
                }
            },"json"
        );
    });
});