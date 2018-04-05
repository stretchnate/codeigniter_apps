<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/2/18
 * Time: 5:41 PM
 */

namespace Plaid;


use API\Vendor;

/**
 * Class Link
 *
 * @package Plaid
 */
class Link {

    /**
     * @var string
     */
    private $integration_js;

    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * Link constructor.
     *
     * @param Vendor $vendor
     * @param array $products
     */
    public function __construct(Vendor $vendor, $products = ['auth', 'transactions', 'identity']) {
        $this->vendor = $vendor;
        $this->integration_js = "
            <!--script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js\"></script-->
            <script src=\"https://cdn.plaid.com/link/v2/stable/link-initialize.js\"></script>
            <script type=\"text/javascript\">
            (function($) {
              var handler = Plaid.create({
                clientName: '".COMPANY_NAME." Bank Account Link',
                env: '%s',
                // Replace with your public_key from the Dashboard
                key: '%s',
                product: ['".implode("','", $products)."'],
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
                  // eventName = \"TRANSITION_VIEW\"
                  // metadata  = {
                  //   link_session_id: \"123-abc\",
                  //   mfa_type:        \"questions\",
                  //   timestamp:       \"2017-09-14T14:42:19.350Z\",
                  //   view_name:       \"MFA\",
                  // }
                }
              });
            
              $('#link_button').on('click', function(e) {
                handler.open();
              });
              $(document).ready(function() {
                handler.open();
              });
            })(jQuery);
            </script>";
    }

    /**
     * @return string
     */
    public function getIntegrationJs() {
        $creds = json_decode($this->vendor->getValues()->getCredentials());

        return sprintf($this->integration_js, $creds->outbound->env, $creds->public_key);
    }
}