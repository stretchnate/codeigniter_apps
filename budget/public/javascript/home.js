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

/**
 * delete funds in account (not category funds)
 * @param {type} account_id
 * @returns {undefined}
 */
function clearAccount(account_id) {
    if(window.confirm('Are you sure you want to delete the leftover funds?')) {
        $.post('/funds/ajaxClearAccount', { account_id: account_id }, function(result) {
            if(result.success === true) {
                var id = 'distribute_'+account_id;
                $('#'+id).text('$0.00');
            } else {
                alert('There was a problem updating the account.');
            }
        }, 'json');
    }
}