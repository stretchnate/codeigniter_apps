    /**
     * This is the main login javascript file
     */

     $(document).ready(function() {
        $("#login_form").submit(function() {
           $("#login_form").validate({
              rules: {
                  login_username: "required",
                  login_password: "required"
              },
              messages: {
                  login_username: "Username is a required field",
                  login_password: "Password is a required field"
              }
           });
           if( !$("#login_form").valid() ) {
//               $("#login_form").submit();
                return false;
           }
        });
     });
