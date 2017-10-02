$(document).ready(function() {
    $('.account-container').hide();

    var id = $('ul.nav-tabs li.active').children('a:first').attr('id').replace(/-tab/, '');
    $('#'+id).show();

    $('ul.nav-tabs li').click(function() {
        if(!$(this).hasClass('active')) {
            $('ul.nav-tabs li.active').removeClass('active');
            $(this).addClass('active');
        }
    });

    $("a.tabs-link").click(function() {
        var account_to_show = $(this).attr("id").replace('-tab', '');
        $(".account-container").hide();
        $("#"+account_to_show).show();
    });
});