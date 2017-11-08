$(function() {
    $('.money').blur(function() {
        //remove all comma's and dollar signs
        var regex    = new RegExp(/[\$,]/g);
        var re_2_dec = new RegExp(/(\.\d{2})\d+/);
        var amount   = $(this).val();
        var dec      = re_2_dec.exec(amount);

        if(dec) {
            amount = amount.replace(re_2_dec, dec[1]);
        }

        amount = amount.replace(regex, '');

        $(this).val(amount);
    });

    /**
     * trim non-numeric chars
     */
    $(".numeric").keyup(function() {
        var new_val = $(this).val().replace(/[\D]/g, '');
        $(this).val(new_val);
    });

    /**
     * remove default value
     */
    $('.form-control').focus(function() {
        var check_val = getCheckVal($(this));
        if($(this).val() === check_val) {
            $(this).val('');
        }
        textToPassword($(this));
    });

    /**
     * add default value
     */
    $('.form-control').blur(function() {
        if(!$(this).val()) {
            var check_val = getCheckVal($(this));
            $(this).val(check_val);
            passwordToText($(this));
        }
    });

    $('.form-control[name=password], .form-control[name=confirm_password], .form-control[name=confirm_new_password], .form-control[name=new_password]').attr('type', 'text');
});

function clearDefaults(form_selector) {
    $(form_selector).each(function() {
        if($(this).val() === getCheckVal($(this))) {
            $(this).val('');
        }
    });
}

function clearForm(selector) {
    $(selector).find(':input').each(function() {
        $(this).val('').blur();
    });
}

/**
 * convert a password field to a text field
 * @param {type} element
 * @returns {undefined}
*/
function passwordToText(element) {
   if(element.attr('type') === 'password') {
       element.attr('type', 'text');
   }
}

/**
 * convert a text field to a password field
 * @param {type} element
 * @returns {undefined}
*/
function textToPassword(element) {
   if(element.attr('name') === 'password'
   || element.attr('name') === 'confirm_password'
   || element.attr('name') === 'confirm_new_password'
   || element.attr('name') === 'new_password') {

       element.attr('type', 'password');
   }
}

/**
 * get the value to check against
 *
 * @param {type} element
 * @returns {unresolved}
*/
function getCheckVal(element) {
   var result = null;
   if(element.attr('id')) {
       result = ucwords(element.attr('id').replace(/_/g, ' '));
   }

   return result;
}

/**
 * upper case all words in a string
 * @param {type} value
 * @returns {unresolved}
*/
function ucwords(value) {
   var words = value.split(' ');
   for(var i in words) {
       words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
   }

   return words.join(' ');
}