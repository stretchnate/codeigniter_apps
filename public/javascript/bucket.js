$(document).ready(function() {
	$("#empty_bucket").click(function() {
		var error = 0;
		var message = "";

		if( $("#bucket_amount").val() < "0.01" ) {
			message = "No funds to distribute in the bucket";
			error++;
		} else if( $("#account").val() == "" ) {
			message = "Please select an account";
			error++;
		}

		if( !error ) {
			$("form[name=empty_bucket_form]").submit();
		} else {
			$("#message").html(message);
		}
	});
});