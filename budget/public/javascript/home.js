$(document).ready(function() {
    $('.account-container').hide();

    if($('ul.nav-tabs li.active').attr('class')) {
        var id = $('ul.nav-tabs li.active').children('a:first').attr('id').replace(/-tab/, '');
        $('#' + id).show();
    }

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

    /**
     * add last payment info for each category
     */
    $(".category_id").each(function() {
        $.get('/ajax/category/getLastPaid/'+$(this).val(), function(result) {
            let category_div = 'category_'+$(this).val();
            if(result.success) {
                let date = new Date(result.data.date);
                let data = date.toLocaleDateString() + ' - $' + result.data.amount;
                $('#'+category_div+' div.last_paid').text(data).removeClass('error');
            } else {
                $('#'+category_div+' div.last_paid').text('unable to load last payment info for this category.').addClass('error');
            }
        }, 'json');
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