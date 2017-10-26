$(document).ready(function() {
    $("#refund").hide();
    $("input[name=operation]").click(function() {
        if($(this).val() == 'refund') {
                $("#refund").show();
        } else {
                $("#refund").hide();
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