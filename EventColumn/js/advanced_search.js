
$(document).ready(function() {
    /**
     * add the soonerThan validator method.
     */
    jQuery.validator.addMethod("soonerThan", function(value, element, other_element) {
        var from_date = new Date(value);
        var to_date = new Date($(other_element).val());
        var result = true;

        if(from_date.getTime() >= to_date.getTime()) {
            result = false;
        }
        return result;
    }, jQuery.validator.format("an event cannot end before it begins"));

    /**
     * add the cityStateOrZip validator method
     */
    jQuery.validator.addMethod('cityStateOrZip', function(value, zip) {
        var result = true;
        var city_val = $("#city").val();
        var state_val = $("#state").val();

        if(!value && (!city_val || !state_val)) {
           result = false;
        }

        return result;
    }, jQuery.validator.format('You must provide a city and state or a zip'));

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
                    minlength: 5,
                    cityStateOrZip: true
                },
                from_date: {
                      date: true,
                      soonerThan: "#to_date"
                },
                to_date: 'date'
            },
            messages: {
                zip: {
                    number: 'zip code must be numeric',
                    minlength: 'zip code must be 5 digits'
                },
                from_date: {
                      date: 'From Date must be a valid date',
                      soonerThan: "From Date must be before To Date"
                },
                to_date: 'To Date must be a valid date'
            }
        });

        //if the form is invalid don't submit it.
        if( form.valid() ) {
            form.submit();
        } else {
            replaceLabels();
        }
    });

    $("#from_date").datetimepicker(timepickerSettings(false));
    $("#to_date").datetimepicker(timepickerSettings(true));
});

/**
 *  replaces the field labels when the field is empty
 *
 * @returns {undefined}
 */
function replaceLabels() {
    $(".toggle_text").each(function() {
        if(!$(this).val()) {
            var id = $(this).attr('id');
            var label = getLabelFromId(id);

            $(this).val(label);
        }
    });
}

/**
 * gets the field label based on the field id
 *
 * @param  id string
 * @return void
 */
function getLabelFromId(id) {
    return id.replace('_', ' ').replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}

/**
 * clears the fields if no value was supplied by the user
 *
 * @returns void
 */
function clearFields() {
    $(".toggle_text").each(function() {
        var label = getLabelFromId($(this).attr('id'));

        if($(this).val() === label) {
           $(this).val('');
        }
    });
}
