    /**
     * This is the main login javascript file
     */

     $(document).ready(function() {
        $("#login-submit").click(function() {
           $("#login_form").validate({
              rules: {
                  username: "required",
                  password: "required"
              },
              messages: {
                  username: "Username is a required field",
                  password: "Password is a required field"
              }
           });
           if($("#login_form").valid()) {
               $("#login_form").submit();
           }
        });
     });
