$(document).ready(function() {
    $("#refund").hide();
    $("#deposits").hide();


    /**
     * show/hide refund transaction id field
     */
    $("select[name=operation]").change(function() {
        $("#refund").hide();
        $("#deposits").hide();
        if($(this).val() == 'refund') {
            $("#refund").show();
        } else if($(this).val() == 'distribution') {
            $('#deposits').show();
        }
    });

    $("form[name=bookEditForm]").submit(function() {
        //remove any comma's in the amount
        $('body').overlay();
        $("input[name=amount]").val($("input[name=amount]").val().replace(/,/g, ''));
        $("form[name=bookEditForm]").validate();
        if( !$("form[name=bookEditForm]").valid()) {
            $('body').overlay('remove');
            return false;
        }
    });


    $("select[name=accounts_select]").change(function() {
        window.location = "/book/getCategory/"+$(this).val()+"/";
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