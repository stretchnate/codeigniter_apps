$(document).ready(function() {

	$("input[name=user_form_submit]").click(function() {
		$("form[name=user_profile_form]").validate( {
			rules: {
				confirm_new_password: {
					equalTo: "#new_password",
				}
			}
		});
		if( $("form[name=user_profile_form]").valid()) {
			$("form[name=user_profile_form]").submit();
		}
	});
});