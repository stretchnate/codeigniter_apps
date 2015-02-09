$(function () {
   $(".main_nav li, .main_nav li ul").mouseover(function() {
        if( $(this).children("ul").attr("class") !== undefined ) {
            $(this).children("ul").show();
        }
    });

    $(".main_nav li, .main_nav li ul").mouseout(function() {
        if( $(this).children("ul").attr("class") !== undefined ) {
            $(this).children("ul").hide();
        }
    });
});