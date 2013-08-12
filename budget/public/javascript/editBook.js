function changeConfirm(process,id){
	inputBox = confirm('Are you sure you want to '+process+' this book?');
	if (inputBox==true){
		window.location.href = "/book/enableBook/"+id+"/";
	}
}

//function validateForm() {//this is broken, need to fix regex.
//	var amount = document.forms[0].amtNec.value;
//	if(amount > 0){
//		var regex = /^?[0-9]+(,[0-9]{3})*(\.[0-9]{2})?$/
//		if(regex.test(amount)){
//			document.forms[0].action = "/book/saveChange/<?php echo $bookId ?>/";
//			return true;
//		} else {
//			alert('Invalid value for Amount Necessary.');
//			return false;
//		}
//	}
//}

$(function() {
	$("form[name=editBookForm]").submit(function() {
		$("form[name=editBookForm]").validate();
		if( !$("form[name=editBookForm]").valid() ) {
			return false;
		}
	});
});