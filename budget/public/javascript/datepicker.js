$(function() {
	$("input[name=date], input[name=start_date], input[name=end_date]").datepicker({
        showOn: "both",
//        buttonImage: 'images/calendar.gif',
        buttonImageOnly: false,
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        nextText: '',
        prevText:''
	});
});