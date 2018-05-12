$(function() {
    $('form[name=loginForm]').submit(function() {
        $(this).overlay('message', 'Authenticating');
        $('form[name=loginForm]').validate();
        if( !$('form[name=loginForm]').valid() ) {
            $(this).overlay('remove');
            return false;
        }
    });
});