$(document).ready(function() {
	$("#newAccountForm").submit(function() {
		$("#ajax-load").toggle();
		$("#result-message").html('');
		$("#newAccountForm").validate();
		if($("#newAccountForm").valid()) {
			$(this).ajaxSubmit({
					target: '.result',
					dataType: "json",
					clearForm: true,
					success: function(data) {
						$('#result-message').html(data.message);
					}
				});
		}
		$("#ajax-load").toggle();
		return false;
	});

	var t = '';
	$("#newAccountForm input[name=name]").keyup(function() {
		if($(this).val()){
			if(t) {
				clearTimeout(t);
			}
			t=window.setTimeout(function(){checkName();}, 250);
		} else {
			$(".ajaxResult").html("Please enter an account name");
		}
	});

	function checkName(){
		$.post("/accountCTL/checkAccountName/",{ name: $("#newAccountForm input[name=name]").val() },
				function(data) {
					$(".ajaxResult").html(data.message);
				},"json"
			);
	}
});