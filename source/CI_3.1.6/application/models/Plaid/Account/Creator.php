<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/11/18
 * Time: 7:19 PM
 */

namespace Plaid\Account;


use Plaid\Connection;
use Plaid\Metadata;

/**
 * Class Creator
 *
 * @package Plaid\Account
 */
class Creator {

    /**
     * @param Metadata $metadata
     * @param \stdClass $token_response
     * @param $owner_id
     * @throws \Exception
     */
    public function run(Metadata $metadata, \stdClass $token_response, $owner_id) {
        foreach($metadata->getAccounts() as $account) {
            $quantum_account = $this->createAccount(
                $metadata->getInstitution()->getName() . ' ' . $account->getName(),
                $owner_id
            );

            $this->createPlaidConnection(
                $token_response->item_id,
                $token_response->access_token,
                $quantum_account->getAccountId(),
                $account->getAccountId()
            );
        }
    }

    /**
     * @param $item_id
     * @param $access_token
     * @param $account_id
     * @param $plaid_account_id
     * @throws \Exception
     */
    private function createPlaidConnection($item_id, $access_token, $account_id, $plaid_account_id) {
        $connection = new Connection();
        $connection->getValues()->setItemId($item_id)
            ->setAccessToken($access_token)
            ->setAccountId($account_id)
            ->setPlaidAccountId($plaid_account_id);

        $connection->save();
    }

    /**
     * @param $name
     * @param $owner_id
     * @return \Budget_DataModel_AccountDM
     * @throws \Exception
     */
    private function createAccount($name, $owner_id) {
        $quantum_account = new \Budget_DataModel_AccountDM();
        $quantum_account->setAccountName($name);
        $quantum_account->setAccountAmount(0);
        $quantum_account->setActive(1);
        $quantum_account->setOwnerId($owner_id);
        $quantum_account->setPayScheduleCode(1);
        $quantum_account->saveAccount();

        return $quantum_account;
    }
}