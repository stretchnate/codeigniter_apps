var Plaid = {
    getAccessToken : function(public_token, metadata) {
        $.post('/ajax/plaid/getAccessToken', {public_token: public_token, metadata : metadata}, function(response) {
            if(response.success) {
                Plaid.transactionsProbe();
            } else {
                //show an error message.
                alert(response.message);
            }
        }, 'json');
    },

    transactionsProbe : function() {
        //probe the server every so often to see if transactions are ready
    }
};