<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Jsincludes {

    const JS         = "/javascript/";
    const UTILITIES  = "<script type='text/javascript' src='/javascript/utilities.js'></script>";
    const JQUERY_UI  = "<script type='text/javascript' src='/javascript/jquery-ui-1.8.21.custom.min.js'></script>";
    const DATATABLES = "<script type='text/javascript' src='/javascript/datatables/jquery.dataTables_1.9.0.min.js'></script>";
    const PLAID      = "<script src='https://cdn.plaid.com/link/v2/stable/link-initialize.js'></script>";

    function newBook() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newBook.js'></script>";
        return $scripts;
    }

    function books() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."books.js'></script>";
        return $scripts;
    }

    function newFunds() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newFunds.js'></script>";
        return $scripts;
    }

    function transferFunds() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."transferFunds.js'></script>";
        return $scripts;
    }

    function editBook() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."editBook.js'></script>";
        return $scripts;
    }

    function home() {
        $scripts[] = SELF::PLAID;
        $scripts[] = "<script type='text/javascript' src='".self::JS."home.js'></script>";
        $scripts[] = "<script type='text/javascript'>
            (function($) {
            var handler = Plaid.create({
                clientName: 'Plaid Walkthrough Demo',
                env: 'sandbox',
                // Replace with your public_key from the Dashboard
                key: '[PUBLIC_KEY]',
                product: ['transactions'],
                // Optional, use webhooks to get transaction and error updates
                webhook: 'https://requestb.in',
                onLoad: function() {
                // Optional, called when Link loads
                },
                onSuccess: function(public_token, metadata) {
                // Send the public_token to your app server.
                // The metadata object contains info about the institution the
                // user selected and the account ID, if the Account Select view
                // is enabled.
                $.post('/get_access_token', {
                    public_token: public_token,
                });
                },
                onExit: function(err, metadata) {
                // The user exited the Link flow.
                if (err != null) {
                    // The user encountered a Plaid API error prior to exiting.
                }
                // metadata contains information about the institution
                // that the user selected and the most recent API request IDs.
                // Storing this information can be helpful for support.
                },
                onEvent: function(eventName, metadata) {
                // Optionally capture Link flow events, streamed through
                // this callback as your users connect an Item to Plaid.
                // For example:
                // eventName = 'TRANSITION_VIEW'
                // metadata  = {
                //   link_session_id: '123-abc',
                //   mfa_type:        'questions',
                //   timestamp:       '2017-09-14T14:42:19.350Z',
                //   view_name:       'MFA',
                // }
                }
            });
            
            $('#link-button').on('click', function(e) {
                handler.open();
            });
            })(jQuery);
        </script>";
        return $scripts;
    }

    function report() {
        $scripts = array();
        return $scripts;
    }

    function newAccount() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newAccount.js'></script>";
        return $scripts;
    }

    public static function getUserProfileJS() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."user_profile.js'></script>";
        return $scripts;
    }

    public static function content() {
        $scripts[] = self::UTILITIES;
        return $scripts;
    }
}