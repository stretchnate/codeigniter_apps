
$(document).ready(function() {
    /**
     * add the soonerThan validator method.
     */
    jQuery.validator.addMethod("soonerThan", function(value, element, other_element) {
        var start_date = new Date(value);
        var end_date = new Date($(other_element).val());
        var result = true;

        if(start_date.getTime() >= end_date.getTime()) {
            result = false;
        }
        return result;
    }, jQuery.validator.format("you cannot end before you begin"));

    /**
     * validate form before submitting
     */
    $('#search_submit').click(function() {
        clearFields();
        var form = $('form[name=advanced_search]');
        form.validate({
            rules: {
                zip: {
                    number: true,
                    minlength: 5
                },
                start_date: {
                      dateISO: true,
                      soonerThan: "#end_date"
                },
                end_date: 'dateISO'
            },
            messages: {
                zip: {
                    number: 'zip code must be numeric',
                    minlength: 'zip code must be 5 digits'
                },
                start_date: {
                      dateISO: 'From Date must be a valid date',
                      soonerThan: "From Date must be before To Date"
                },
                end_date: 'To Date must be a valid date'
            }
        });

        //if the form is invalid don't submit it.
        if( form.valid() ) {
            form.submit();
        }
    });
});

/**
 * clears the fields if no value was supplied by the user
 *
 * @returns void
 */
function clearFields() {
    if($('#event_title').val() == 'Event Title') {
        $('#event_title').val('');
    }

    if($('#city').val() == 'City') {
        $('#city').val('');
    }

    if($('#state').val() == 'State') {
        $('#state').val('');
    }

    if($('#zip').val() == 'Zip') {
        $('#zip').val('');
    }

    if($('#start_date').val() == 'From Date') {
        $('#start_date').val('');
    }

    if($('#end_date').val() == 'To Date') {
        $('#end_date').val('');
    }
}
