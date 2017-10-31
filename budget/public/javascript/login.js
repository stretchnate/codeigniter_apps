$(function() {
    $('form[name=loginForm]').submit(function() {
        $('form[name=loginForm]').validate();
        if( !$('form[name=loginForm]').valid() ) {
            return false;
        }
    });
});