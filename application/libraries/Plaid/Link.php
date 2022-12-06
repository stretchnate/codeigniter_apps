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
     * @var array
     */
    private $products;

    /**
     * Link constructor.
     *
     * @param Vendor $vendor
     * @param array $products
     */
    public function __construct(Vendor $vendor, $products = ['auth', 'transactions']) {
        $this->vendor = $vendor;
        $this->products = $products;
    }

    /**
     * @return string
     */
    private function buildIntegrationJs($existing_account = false) {
        $notice = null;
        if($existing_account) {
            $notice = /** @lang javascript */
            "if(eventName == 'TRANSITION_VIEW' && metadata.view_name == 'SELECT_ACCOUNT') {
                alert('You are about to link an existing account, please select only the account you wish to link (i.e. this account), otherwise the wrong account may get linked');
            }";
        }
        return $this->integration_js =
        "<script src=\"https://cdn.plaid.com/link/v2/stable/link-initialize.js\"></script>
            <script type=\"text/javascript\">
            (function($) {
              var handler = Plaid.create({
                clientName: '".COMPANY_NAME." Bank Account Link',
                env: '%s',
                key: '%s',
                product: ['".implode("','", $this->products)."'],
                webhook: '".base_url('webhook/plaid/transactions')."',
                onLoad: function() {
                  // Optional, called when Link loads
                },
                onSuccess: function(public_token, metadata) {
                  $('body').overlay('message', 'Gathering Account Information');
                  plaid.getAccessToken(public_token, metadata);
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
                    {$notice}
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
            
              $(document).ready(function() {
                  $('#link_button').on('click', function(e) {
                    handler.open();
                  });
                  %s
               });
            })(jQuery);
            </script>";
    }

    /**
     * @return string
     */
    public function getAutoLoadIntegrationJs() {
        return $this->getIntegrationJs('handler.open()', false);
    }

    /**
     * @param string $extra_js
     * @param bool $existing_account
     * @return string
     */
    public function getIntegrationJs($extra_js = null, $existing_account = true) {
        $this->buildIntegrationJs($existing_account);
        $creds = json_decode($this->vendor->getValues()->getCredentials());

        return sprintf($this->integration_js, $creds->outbound->env, $creds->public_key, $extra_js);
    }
}