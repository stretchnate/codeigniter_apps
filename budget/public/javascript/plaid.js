var plaid = {};

//for some reason this isn't being found
plaid.getAccessToken = function(public_token, metadata) {
    $.post('/ajax/plaid/getAccessToken', {public_token: public_token, metadata : metadata}, function(response) {
        if(response.success) {
            plaid.transactionsProbe();
        } else {
            //show an error message.
            alert(response.message);
        }
    }, 'json');
};

plaid.transactionsProbe = function() {
    //probe the server every so often to see if transactions are ready
}