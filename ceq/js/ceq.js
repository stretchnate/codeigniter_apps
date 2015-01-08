
    $(document).ready(function() {
        /**
         * report form submission
         */
        $("#submit_report").click(function() {
            $("#report_form").validate({
                rules: {
                    home_teachers: "required",
                    family: "required",
                    assessment: "required"
                },
                messages: {
                    home_teachers: "Home Teacher is a required field",
                    family: "Family is a required field",
                    assessment: "Please indicate the type of visit/contact you had with this family"
                },
                errorPlacement: function(error, element) {
                    if (element[0].id.match(/assessment/)) {
                        $('label#contact_label').append(error);
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            if($("#report_form").valid()) {
                $("#report_form").submit();
            }
        });
    });


