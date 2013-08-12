var t = "";
$(document).ready(function() {
	function changeCharitable() {
		if($("#calc").val() == 3) {
			$("#priority").val('10');
			$("#registerForm input[name=multiplier]").val("");
			$("#registerForm input[name=multiplier],#priority").attr("disabled","disabled");
		} else {
			$("#registerForm input[name=multiplier],#priority").removeAttr("disabled");
		}
	}
	
	function showCharitable() {
		if($("#registerForm input[name=charitable]:checked").val() == 1) {
			$("#main").css("float","left");
			$("#charitableAcct").show();
		} else {
			$("#main").css("float","none");
			$("#charitableAcct").hide();
		}
	}
	
	function checkName(){
		$.post("/admin/checkName/",{ username: $("#registerForm input[name=username]").val() },
			function(data) {
				if(data.success == true) {
					$(".ajaxResult").css("color","blue");//change this color sometime
				} else {
					$(".ajaxResult").css("color","#ff3333");
				}
				$(".ajaxResult").html(data.message);
			},"json"
		);
	}
	$("#registerForm input[name=username]").keyup(function() {
		if($(this).val().length > 3) {
			if(t) {
				clearTimeout(t);
			}
			t=window.setTimeout(function(){checkName();}, 400);
		} else {
			$(".ajaxResult").html("Must be at least 4 Characters");
		}
	});
	$("#registerForm input[name=charitable]").click(function() {
		showCharitable();
	});
	$("#calc").change(function() {
		changeCharitable();
	});
	$('.tool-tip').tooltip({ 
		track: true, 
	    delay: 0, 
	    showURL: false, 
	    opacity: .85, 
	    fixPNG: true, 
	    top: 15, 
	    left: 5 
	});
	$("form[name=registerForm]").submit(function() {
		$(this).validate({
			rules: {
				username: { minlength: 4 },
				password: { minlength: 6 },
				confirmPassword: { minlength: 6 }
			}
		});
		if( !$(this).valid() ) {
			return false;
		}
	});
	showCharitable();
	changeCharitable();
});