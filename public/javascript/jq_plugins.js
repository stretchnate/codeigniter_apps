
(function( $ ) {
    /**
     * overlay plugin adds an overlay div with loading gif and message to an element
     * passing 'remove' as an argument will remove the overlay
     *
     * @param options
     * @param value
     */
    $.fn.overlay = function(options, value) {
        if(typeof options === 'string') {
            if(value) {
                switch(options) {
                    case 'action': options = {action: value}; break;
                    case 'loader': options = {loader: value}; break;
                    case 'message': options = {message: value}; break;
                }
            } else {
                options = {action: options};
            }
        }

        var settings = $.extend({
            action: 'open',
            loader: '/images/ajax-loader.gif',
            message: ""
        }, options );

        if ( settings.action === "remove" ) {
            this.children('.overlay').remove();
        } else {
            if(this.children('.overlay').length < 1) {
                this.append("<div class='overlay'><img id='loader' src='"+settings.loader+"' alt='Loading...' /><div id='message'>"+settings.message+"</div></div>");
            }
            this.children('.overlay').show();
        }
    };
}(jQuery));