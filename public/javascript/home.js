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
        let category_id = $(this).val();
        let category_div = 'category_'+category_id;
        if($('#'+category_div+' div.last_paid').length) {
            $.get('/ajax/category/getLastPaid/' + category_id, function (result) {
                if (result.success) {
                    let due_date = new Date($('#' + category_div).parent().find('label.due-date').text());
                    let date = new Date(result.data.date);
                    let data = date.toLocaleDateString() + ' - $' + result.data.amount;
                    $('#' + category_div + ' div.last_paid').text(data).removeClass('error');
                    $('#' + category_div).addClass(getPaidClass(due_date));
                } else {
                    $('#' + category_div + ' div.last_paid').text('unable to load last payment info for this category.').addClass('error');
                }
            }, 'json');
        }
    });
});

/**
 *
 * @param paid_date
 * @returns {string}
 */
function getPaidClass(due_date) {
    let days = getDaysUntilDue(due_date);

    return (days > 14) ? 'paid' : 'unpaid';
}

/**
 *
 * @param paid_date
 * @returns {number}
 */
function getDaysUntilDue(due_date) {
    let today = new Date();
    return Math.round((due_date-today)/(1000*60*60*24));
}

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