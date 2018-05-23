<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/22/2018
 * Time: 7:16 PM
 */

namespace Plaid\Response;

use Plaid\Plaid;
use Plaid\Response;
use Plaid\Transaction;

class Helper {

    /**
     * @param Plaid $plaid_response
     * @param string $product
     */
    public function saveResponse($plaid_response, $product) {//todo - MAKE THIS WORK WITH PLAID OBJECTS (FIX TOKEN EXCHANGE)
        try {
            $response = new Response();
            $response->getValues()->setProduct($product)
                ->setData(json_encode($plaid_response))
                ->setRequestId($plaid_response->request_id);

            $response->save();
        } catch(\Exception $e) {
            log_message('error', "unable to save plaid response");
            log_message('error', json_encode($plaid_response));
        }
    }

    /**
     * @param Plaid $plaid_response
     */
    public function saveTransactionResponse($plaid_response) {
        try {
            $transaction = new Transaction();
            $transaction->getValues()->setData(json_encode($plaid_response->getRawResponse()))
                ->setRequestId($plaid_response->getRawResponse()->request_id);

            $transaction->save();
        } catch(\Exception $e) {
            log_message('error', "unable to save plaid transaction response");
            log_message('error', json_encode($plaid_response->getRawResponse()));
        }
    }
}