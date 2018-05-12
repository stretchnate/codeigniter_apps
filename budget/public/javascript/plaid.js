var plaid = {probe:null};

/**
 * exchange the public token for the access token
 *
 * @param public_token
 * @param metadata
 */
plaid.getAccessToken = function(public_token, metadata) {
    $.post('/ajax/plaid/getAccessToken', {public_token: public_token, metadata : metadata}, function(response) {
        if(response.success) {
            $('body .overlay #message').text('Gathering Transactions');
            plaid.probe = window.setInterval(plaid.transactionsProbe, 5000, metadata['account_id']);
        } else {
            //show an error message.
            alert(response.message);
        }
    }, 'json');
};

/**
 * check to see if transactions are ready
 */
plaid.transactionsProbe = function(account_id) {
        $.post('/ajax/plaid/areTransactionsReady', {plaid_account_id:account_id}, function(response) {
            if(response.success) {
                if(response.message == 'yes') {
                    clearInterval(plaid.probe);
                    plaid.handleTransactions(account_id);
                }
            } else {
                clearInterval(probe);
                alert('there was a problem linking your account(s).');
            }
        }, 'json');
};

plaid.handleTransactions = function(account_id) {
    var date = new Date();
    var month = date.getMonth() == 0 ? 12 : date.getMonth();
    var start_date = date.getFullYear()+'-'+month+'-'+date.getDate();

    $('body .overlay #message').text('Creating Categories');
    $.post('/ajax/plaid/handleTransactions', {plaid_account_id:account_id, start_date:start_date}, function(response) {
        if(response.success) {
            $('body .overlay #message').text('Success!');
            location.reload();
        } else {
            $('body').overlay('remove');
            alert('there was a problem linking your account(s)');
        }
    }, 'json');
};