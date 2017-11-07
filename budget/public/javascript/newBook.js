$(document).ready(function () {
    $("#submit_category").click(function () {
        clearDefaults("#newBookForm input[type=text]");
//        $("#ajax-load").show();
//        $("#result-message").html('');
        $("#newBookForm").validate();
        if ($("#newBookForm").valid()) {
            $.post($("#newBookForm").attr('action'), $("#newBookForm").serialize(), function(response) {
                var new_class = 'text-info';
                var old_class = 'text-danger';
                if(!response.success) {
                    new_class = 'text-danger';
                    old_class = 'text-info';
                }
                $('#result-message').removeClass(old_class).addClass(new_class).html(response.message);
            });
//            $("#ajax-load").hide();
        }
        return false;
    });
    $("#newBookForm input[name=startAmt],#newBookForm input[name=nec]").focus(function () {
        $(this).val("");
    });

    var t = '';
    $("#newBookForm input[name=name]").keyup(function () {
        if ($(this).val()) {
            if (t) {
                clearTimeout(t);
            }
            t = window.setTimeout(function () {
                checkName();
            }, 250);
        } else {
            $(".ajaxResult").html("Please enter an account name");
        }
    });
    function checkName() {
        $.post("/book/checkName/", {name: $("#newBookForm input[name=name]").val(), account: $("#newBookForm select[name=account]").val()},
            function (data) {
                $(".ajaxResult").html(data.message);
            }, "json"
        );
    }
});