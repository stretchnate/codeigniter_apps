    /**
     * This is the main js file for the EventColumn site. Any js functionality that can be reused
     * on multiple pages should be in this file.
     */

     $(document).ready(function() {
        $("#mini_search_zip").focus(function() {
           var default_message = 'search events by zip code';
           if($(this).val() == default_message) {
               $(this).val('');
           }
        });

        $("#mini_search_zip").blur(function() {
           var default_message = 'search events by zip code';
           if($(this).val() == '') {
               $(this).val(default_message);
           }
        });

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

        $("#mini_search_zip").keyup(function() {
           var pattern = /[^\d]/g;
           $("#mini_search_zip").val($("#mini_search_zip").val().replace(pattern, ''));
        });
     });

