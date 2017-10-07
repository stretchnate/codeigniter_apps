$(document).ready(function () {
    $("#newBookForm").submit(function () {
        $("#ajax-load").show();
        $("#result-message").html('');
        $("#newBookForm").validate();
        if ($("#newBookForm").valid()) {
            $(this).ajaxSubmit({
                target: '.result',
                dataType: "json",
                clearForm: true,
                success: function (data) {
                    $('#result-message').html(data.message);
                    $("#ajax-load").hide();
                }
            });
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