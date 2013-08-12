$(document).ready(function() {
	$(".interest-info input").attr("disabled","disabled");
	$("#newBookForm input[name=interest]").removeAttr("disabled");
	$("#newBookForm input[name=dontknow],#newBookForm input[name=interest]").removeAttr("checked");
	$("#newBookForm input[name=interest]").click(function() {
		if($(this).val() == 1) {
			if($("select option:selected").val() == 4) {
				$("#newBookForm input[name=totalOwed]").removeAttr("disabled");
			} else {
				$(".interest-info input").removeAttr("disabled");
			}
		} else {
			$(".interest-info input").attr("disabled","disabled");
		}
	});
	$("#rateType").change(function() {
		if($(this).val() == "4") {
			$("#newBookForm input[name=rate]").attr("disabled","disabled");
		} else {
			if($("#newBookForm input[name=interest]:checked").val() == 1) {
				$("#newBookForm input[name=rate]").removeAttr("disabled");
			}
		}
	});
	$("#newBookForm").submit(function() {
		$("#ajax-load").show();
		$("#result-message").html('');
		$("#newBookForm").validate();
		if($("#newBookForm").valid()) {
			$(this).ajaxSubmit({
					target: '.result',
					dataType: "json",
					clearForm: true,
					success: function(data) {
						$('#result-message').html(data.message);
						$("#ajax-load").hide();
					}
				});
		}
		return false;
	});
	$("#newBookForm input[name=startAmt],#newBookForm input[name=nec]").focus(function() {
		$(this).val("");
	});

	var t = '';
	$("#newBookForm input[name=name]").keyup(function() {
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
		$.post("/book/checkName/",{ name: $("#newBookForm input[name=name]").val(), account: $("#newBookForm select[name=account]").val() },
				function(data) {
					$(".ajaxResult").html(data.message);
				},"json"
			);
	}
	$("#newBookForm input[name=nec]").blur(function() {
		if(!$(this).val()){
			$("#amt-message").text("It's not reccommended to have a 0 amount here.");
		} else {
			$("#amt-message").text("");
		}
	});
	$("#newBookForm input[name=interest]").attr("checked","checked");
});