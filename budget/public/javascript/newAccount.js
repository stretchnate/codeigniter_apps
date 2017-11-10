$(document).ready(function() {
    $("#add_account").click(function() {
        clearDefaults("#newAccountForm input[type=text]");
        $("#ajax-load").toggle();
        $("#result-message").text('');
        $("#newAccountForm").validate();
        if($("#newAccountForm").valid()) {
            $.post($("#newAccountForm").attr('action'), $("#newAccountForm").serialize(), function(data) {
                var new_class = 'text-danger';
                var old_class = 'text-info';
                if(data.success) {
                    new_class = 'text-info';
                    old_class = 'text-danger';
                }

                $('#result-message').removeClass(old_class).addClass(new_class).text(data.message);
            }, 'json');
        }
        $("#ajax-load").toggle();
        return false;
    });

    var t = '';
    $("#newAccountForm input[name=name]").keyup(function() {
        if($(this).val()){
            if(t) {
                clearTimeout(t);
            }
            t=window.setTimeout(function(){checkName();}, 250);
        } else {
            $(".ajaxResult").html("Please enter an account name");
        }
    });

    function checkName(){
        $.post("/accountCTL/checkAccountName/",{ name: $("#newAccountForm input[name=name]").val() },
            function(data) {
                $(".ajaxResult").html(data.message);
            },"json"
        );
    }
});