    /**
     * This is the main register javascript file
     */

     $(document).ready(function() {
         jQuery.validator.addMethod("validValues", function(value, element, expression) {
             var regex = new RegExp(expression);
             return this.optional(element) || regex.test(value);
         }, jQuery.validator.format("{0} must include only values from {1}"));

         jQuery.validator.addMethod("passwordCheck", function(value, element, expression) {
             return this.optional(element) || (value.length < 8 || value.length > 32);
         }, jQuery.validator.format("{0} must be between 8 and 32 characters"));
        $("#register_form").submit(function() {
           $("#register_form").validate({
              rules: {
                  username: "required",
                  password: {
                      required: true,
                      rangelength: [8,32],
                      validValues: "^[\\w!\\$@%\\*&\\^\\?\\|\\+=\\.-]{8,32}$"
                  },
//                  confirm_password: {
//                      required: true,
//                      equalTo: "#password"
//                  },
                  email: {
                      required: true,
                      email: true
                  },
//                  confirm_email: {
//                      required: true,
//                      email: true,
//                      equalTo: "#email"
//                  },
                  agree_to_terms_and_policies: "required"
              },
              messages: {
                  username: "Username is a required field",
                  password: {
                      required: "Password is a required field",
                      rangelength: "Password must be between 8 and 32 characters long",
                      validValues: "Password characters can be a-z,A-Z,0-9,_!$@%*&^?|+=.-"
                  },
//                  confirm_password: {
//                      required: "Please re-type your password",
//                      equalTo: "Confirm Password must equal Password"
//                  },
                  email: {
                      required: "Email is required",
                      email: "Email has an invalid email address"
                  },
//                  confirm_email: {
//                      required: "Confirm Email is required",
//                      email: "Confirm Email has an invalid email address",
//                      equalTo: "Confirm Email must match Email"
//                  },
                  agree_to_terms_and_policies: "please agree to our terms and policies"
              }
           });

            if(!$("#register_form").valid()) {
               return false;
           }
        });

        $("#user_profile_form").submit(function() {
            if($("input[name=current_email]").val() == $("#email").val()) {
                $("#email").attr('disabled', 'disabled');
            }

            $("#user_profile_form").validate({
                rules: {
                    current_password: {
                        required: true
                    },
                    new_password: {
                        rangelength: [8, 32],
                        validValues: "^[\\w!\\$@%\\*&\\^\\?\\|\\+=\\.-]{8,32}$"
                    },
                    confirm_new_password: {
                        equalTo: "#new_password"
                    },
                    email: {
                        email: true
                    }
                },
                messages: {
                    password: {
                        required: "You must provide your current password to make any change to your profile.",
                    },
                    new_password: {
                        rangelength: "Your new password must be between 8 and 32 characters long",
                        validValues: "Password characters can be a-z,A-Z,0-9,_!$@%*&^?|+=.-"
                    },
                    confirm_new_password: {
                        equalTo: "Confirm New Password must match New Password"
                    },
                    email: {
                        email: "Invalid email address"
                    }
                }
            });
            if ($("#user_profile_form").valid()) {
                $("#user_profile_form").submit();
            } else {
                $("#email").removeAttr('disabled');
            }

            return false;
        });
    });

