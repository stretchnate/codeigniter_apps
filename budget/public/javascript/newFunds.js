$(function() {
    $("form[name=newFundsForm]").submit(function() {
        $("form[name=newFundsForm]").validate();
        if($("form[name=newFundsForm]").valid()) {
            if($("#manual").prop("checked")) {
                $(this).attr("action","/funds/addFunds/");
            } else {
                $(this).attr("action","/funds/automaticallyDistributeFunds/");
            }
        } else {
            return false;
        }
    });
});