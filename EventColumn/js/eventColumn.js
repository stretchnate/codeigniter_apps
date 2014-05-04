    /**
     * This is the main js file for the EventColumn site. Any js functionality that can be reused
     * on multiple pages should be in this file.
     */

     $(document).ready(function() {
        var default_vals = new Array();
        var default_types = new Array();

        /**
         * clear the mini search bar
         */
        $("#mini_search_zip").focus(function() {
           var default_message = 'search events by zip code';
           if($(this).val() == default_message) {
               $(this).val('');
           }
        });

        /**
         * default message for mini search bar
         */
        $("#mini_search_zip").blur(function() {
           var default_message = 'search events by zip code';
           if($(this).val() == '') {
               $(this).val(default_message);
           }
        });

        /**
         * mini search submit action
         */
        $("#mini_search_submit").click(function() {
           $("#mini_search").validate({
              rules: {
                  mini_search_zip: {
                      required: true,
                      number: true,
                      maxlength: 5,
                      minlength: 5
                  }
              },
              messages: {
                  mini_search_zip: {
                      required: 'please provide a zip code to search',
                      number: 'please provide a numeric zip code',
                      maxlength: 'zip code must be 5 characters',
                      minlength: 'zip code must be 5 characters'
                  }
              }
           });
           if($("#mini_search").valid()) {
               $("#mini_search").submit();
           }
        });

        /**
         * force numerical zip on mini search
         */
        $("#mini_search_zip").keyup(function() {
           var pattern = /[^\d]/g;
           $("#mini_search_zip").val($("#mini_search_zip").val().replace(pattern, ''));
        });

        /**
         * replace input type (e.g. text to password)
         */
        $(".replace_type").focus(function() {
            var types = $(this).attr("class").split(" ");
            var new_type = $(this).attr("type");
            var regex = /^new_type_/;

            if(default_types[$(this).attr("id")] === undefined) {
                default_types[$(this).attr("id")] = $(this).attr("type");
            }

            for(var i in types) {
                if(regex.test(types[i]) === true) {
                    new_type = types[i].substring(9);
                    break;
                }
            }

            $(this).attr("type", new_type);
        });

        /**
         * switch type back if neccessary (e.g. password to text)
         */
        $(".replace_type").blur(function() {
            if($(this).val() == '' && default_types[$(this).attr("id")] !== undefined) {
                $(this).attr("type", default_types[$(this).attr("id")]);
            }
        });

        /**
         * toggle input text (default messages)
         */
        $(".toggle_text").focus(function() {
            if(default_vals[$(this).attr("id")] === undefined) {
                default_vals[$(this).attr("id")] = $(this).val();
            }

            if($(this).val() === default_vals[$(this).attr("id")]) {
                $(this).val("");
            }
        });

        /**
         * toggle input text (default messages)
         */
        
        $(".toggle_text").blur(function() {
            if($(this).val() == '') {
                $(this).val(default_vals[$(this).attr("id")]);
            }
        });
     });

     function timepickerSettings(return_beforeShow) {
         var tp_object = {
           hourGrid: 6,
           minuteGrid: 15,
           timeFormat: 'h:mm tt'
       };

       if(return_beforeShow === true) {
           tp_object.beforeShow = getEarliestEndDate;
       }

       return tp_object;
     }

    function getEarliestEndDate(input, inst) {
        var today = new Date();
        var start = new Date($("#event_start").val());
        var result = (today.getMonth()+1) + '/' + today.getDate() + '/' + today.getFullYear();

        if(today.getTime() < start.getTime()) {
            result = (start.getMonth()+1) + '/' + start.getDate() + '/' + start.getFullYear();
        }

        inst.settings.minDate = result;

        return inst;
    }