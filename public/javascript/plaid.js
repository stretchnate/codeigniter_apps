var plaid = {probe:null};

/**
 * exchange the public token for the access token
 *
 * @param public_token
 * @param metadata
 */
plaid.getAccessToken = function(public_token, metadata) {
    var existing_account = $('#plaid_existing_account_id').length ? $('#plaid_existing_account_id').val() : null;
    $.post('/ajax/plaid/getAccessToken', {public_token: public_token, metadata : metadata, existing_account : existing_account}, function(response) {
        if(response.success) {
            $('body .overlay #message').text('Gathering Transactions');
            plaid.probe = window.setInterval(plaid.transactionsProbe, 5000, metadata['account_id']);
        } else {
            //show an error message.
            $('body').overlay('remove');
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

/**
 * convert plaid transactions to account/categories and quantum transactions
 * @param account_id
 */
plaid.handleTransactions = function(account_id) {
    var date = new Date();
    date.setDate(date.getDate()-31);
    var start_date = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();

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

/**
 * add an element to hold the account id we want to link
 * @param account_id
 */
plaid.linkExistingAccount = function(account_id) {
    if($('body').find('plaid_existing_account_id').length) {
        $('#plaid_existing_account_id').remove();
    }
    $('body').append('<input type="hidden" value="' + account_id + '" id="plaid_existing_account_id">');
}