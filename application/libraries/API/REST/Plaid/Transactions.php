<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 8:58 PM
 */

namespace API\REST\Plaid;


use API\REST\Plaid;

class Transactions extends Plaid {

    private $target = 'transactions/get';

    /**
     * @param $access_token
     * @param $account_id
     * @param $start_date
     * @param $end_date
     * @param int $count
     * @param int $offset
     * @return \Plaid\TransactionResponse
     * @throws \Exception
     */
    public function getTransactions($access_token, $account_id, \DateTime $start_date, \DateTime $end_date = null, $count = 300, $offset = 0) {
        $this->start();

        if(!$end_date) {
            $end_date = new \DateTime();
        }

        $postfields = $this->dataArray($access_token);
        $postfields['start_date'] = $start_date->format('Y-m-d');
        if($end_date) {
            $postfields['end_date'] = $end_date->format('Y-m-d');
        }
        $postfields['options'] = [
            'account_ids' => [$account_id],
            'count' => $count,
            'offset' => $offset
        ];

        $response = $this->post($this->target, json_encode($postfields));

        return new \Plaid\TransactionResponse($this->parseResponse($response));
    }
}