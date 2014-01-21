    /**
     * This is the main register javascript file
     */

     $(document).ready(function() {
         jQuery.validator.addMethod("validValues", function(value, element, expression) {
             var regex = new RegExp(expression);
             return regex.test(value);
         }, jQuery.validator.format("{0} must include only values from {1}"));

        $("#register_submit").click(function() {
           $("#register_form").validate({
              rules: {
                  username: "required",
                  password: {
                      required: true,
                      rangelength: [8,32],
                      validValues: "^[\\w!\\$@%\\*&\\^\\?\\|\\+=\\.-]{8,32}$"
                  },
                  password_retype: {
                      required: true,
                      equalTo: "#password"
                  },
                  email: {
                      required: true,
                      email: true
                  },
                  confirm_email: {
                      required: true,
                      email: true,
                      equalTo: "#email"
                  },
                  agree_to_terms: "required"
              },
              messages: {
                  username: "Username is a required field",
                  password: {
                      required: "Password is a required field",
                      rangelength: "Password must be between 8 and 32 characters long",
                      validValues: "Password characters can be a-z,A-Z,0-9,_!$@%*&^?|+=.-"
                  },
                  password_retype: {
                      required: "Please re-type your password",
                      equalTo: "Confirm Password must equal Password"
                  },
                  email: {
                      required: "Email is required",
                      email: "Email has an invalid email address"
                  },
                  confirm_email: {
                      required: "Confirm Email is required",
                      email: "Confirm Email has an invalid email address",
                      equalTo: "Confirm Email must match Email"
                  },
                  agree_to_terms: "please agree to our terms and policies"
              }
           });
           if($("#register_form").valid()) {
//               $("#register_form").submit();
           }
        });
     });

