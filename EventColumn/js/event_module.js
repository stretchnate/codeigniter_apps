    /**
     * This is the main javascript file for the event module
     *
     * @author stretch
     * @since 1.0
     */

    function submitEvent() {
        $("#event_add_form").submit();
    }

    /**
     * converts an address into lattitude logitude coordinates.
     *
     * @param {form} event_form
     * @returns {string}
     */
    function geocode(event_form) {
        var geocoder = new google.maps.Geocoder();
        var location = $("input[name='event_details_locations[0][event_address]']").val();

        location += ", " + $("input[name='event_details_locations[0][event_city]']").val();
        location += ", " + $("input[name='event_details_locations[0][event_state]']").val();
        location += " " + $("input[name='event_details_locations[0][event_zip]']").val();

        geocoder.geocode({'address': location}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var lat_long = results[0].geometry.location.toString().replace(/(\(|\))/g, '');
                $("input[name='event_details_locations[0][lat_long]']").val(lat_long);
                submitEvent();
            } else {
                $("input[name='event_details_locations[0][lat_long]']").val("unable to get coordinates");
                alert("unable to convert "+location+" to coordinates");
            }
        });
    }

    $(document).ready(function() {
       $("#event-submit").click(function() {
          $("#event_add_form").validate({
                rules: {
                    event_name: "required",
                    event_start_datetime: "required",
                    event_end_datetime: "required",
                    'event_details_locations[0][event_location_name]': "required",
                    'event_details_locations[0][event_address]': "required",
                    'event_details_locations[0][event_city]': "required",
                    'event_details_locations[0][event_state]': "required",
                    'event_details_locations[0][event_zip]': "required",
                    'event_details_locations[0][event_country]': "required",
                    'event_details_locations[0][age]': "required",
                    'event_description': "required",
                    'event_category': "required"
                },
                messages: {
                    event_name: "Event Name is required",
                    event_start_datetime: "Start Date is required",
                    event_end_datetime: "End Date is required",
                    'event_details_locations[0][event_location_name]': "Location Name is required",
                    'event_details_locations[0][event_address]': "Address is required",
                    'event_details_locations[0][event_city]': "City is required",
                    'event_details_locations[0][event_state]': "State is required",
                    'event_details_locations[0][event_zip]': "Zip is required",
                    'event_details_locations[0][event_country]': "Country is required",
                    'event_details_locations[0][age]': "Age Range is required",
                    'event_description': "Event Description is required",
                    'event_category': "Category is required"
                }
            });
          if($("#event_add_form").valid()) {
              geocode();
          }
       });
    });
