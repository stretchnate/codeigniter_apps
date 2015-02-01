
    $(document).ready(function() {
        /**
         * report form submission
         */
        $("#submit_report").click(function() {
            $("#report_form").validate({
                rules: {
                    home_teacher: "required",
                    family: "required",
                    date_of_visit: {
                        required: true,
                        dateISO: true
                    },
                    assessment: "required"
                },
                messages: {
                    home_teacher: "Home Teacher is a required field",
                    family: "Family is a required field",
                    date_of_visit: {
                        required: "Date of Visit is a required field",
                        dateISO: "Please enter a valid date (yyyy-mm-dd)"
                    },
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

        /**
         * datepicker for date_of_visit field
         */
        $("input[name=date_of_visit]").datepicker({
            showOn: "both",
            buttonImageOnly: false,
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            nextText: '',
            prevText:''
	});
    });


