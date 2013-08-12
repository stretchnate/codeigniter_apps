$(document).ready(function() {
	$("input[name=transfer-funds]").change(function() {
		if( $("input[name=transfer-funds]:checked").val() == "from-accounts-radio" ) {
			$(".transfer-categories").hide();
			$(".transfer-accounts").show();
			$("#transferForm").attr("action", "/fundsTransferCTL/transferAccounts/");
		} else {
			$(".transfer-categories").show();
			$(".transfer-accounts").hide();
			$("#transferForm").attr("action", "/fundsTransferCTL/transferCategories/");
		}
	});
	$("input[name=transfer-funds]").change();

	$("form[name=transferForm]").submit(function() {
		$(this).validate();
		if( !$(this).valid() ) {
			return false;
		}
	});
});