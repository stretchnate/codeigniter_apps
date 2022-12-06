$(document).ready(function() {
    /**
     * load categories select on account select change
     */
    $("#account").change(function() {
        if($(this).val() != '') {
            $.post('/Report/fetchCategoryDropdown', {account_id:$(this).val()}, function(result) {
                if(result.success) {
                    $('#categories-container').html(result.data);
                } else {
                    alert('Oops, there was a problem loading the categories. Please try again.');
                }
            }, 'json')
        }
    });
})