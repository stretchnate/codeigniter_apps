function changeConfirm(process,id){
    inputBox = confirm('Are you sure you want to '+process+' this book?');
    if (inputBox === true){
        window.location.href = "/book/enableBook/"+id+"/";
    }
}

$(function() {
    $("form[name=editBookForm]").submit(function() {
        $("form[name=editBookForm]").validate();
        if( !$("form[name=editBookForm]").valid() ) {
            return false;
        }
    });

    $('#due_date_checkbox').change(function() {
        if($(this).is(':checked')) {
            $('#due_date').val('').attr('disabled', 'disabled');
        } else {
            $('#due_date').val('Due Date').removeAttr('disabled');
        }
    });
});
