$(function() {
    $("form[name=newFundsForm]").submit(function() {
        $("form[name=newFundsForm]").validate();
        if($("form[name=newFundsForm]").valid()) {
            if($("input[name=manual]").attr("checked") == true || $("input[name=manual]").attr("checked") == "checked") {
                $(this).attr("action","/funds/addFunds/");
            } else {
                $(this).attr("action","/funds/automaticallyDistributeFunds/");
            }
        } else {
            return false;
        }
    });
});