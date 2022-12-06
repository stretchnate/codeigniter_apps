<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/11/18
 * Time: 7:19 PM
 */

namespace Plaid\Account;


use Plaid\AccessToken;
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
     * @param AccessToken $token_response
     * @param $owner_id
     * @throws \Exception
     */
    public function run(Metadata $metadata, AccessToken $token_response, $owner_id, $existing_account_id = null) {
        foreach($metadata->getAccounts() as $account) {
            if($existing_account_id) {
                $quantum_account = new \Budget_DataModel_AccountDM($existing_account_id, $owner_id);
                if($quantum_account->getAccountType() != $account->getSubtype()) {
                    throw new \Exception('tried linking account of '.$account->getSubtype().' to account type of '.$quantum_account->getAccountType(), EXCEPTION_CODE_VALIDATION);
                }
            }

            if(!isset($quantum_account)) {
                $quantum_account = $this->createAccount(
                    $metadata->getInstitution()->getName() . ' ' . $account->getName(),
                    $owner_id,
                    $account->getSubtype()
                );
            }

            $this->createPlaidConnection(
                $token_response->getItemId(),
                $token_response->getAccessToken(),
                $quantum_account->getAccountId(),
                $account->getAccountId()
            );

            unset($quantum_account);
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
     * @param $account_type
     * @return \Budget_DataModel_AccountDM
     * @throws \Exception
     */
    private function createAccount($name, $owner_id, $account_type = 'checking') {
        $quantum_account = new \Budget_DataModel_AccountDM();
        $quantum_account->setAccountName($name);
        $quantum_account->setAccountType($account_type);
        $quantum_account->setAccountAmount(0);
        $quantum_account->setActive(1);
        $quantum_account->setOwnerId($owner_id);
        $quantum_account->setPayScheduleCode(1);
        $quantum_account->saveAccount();

        return $quantum_account;
    }
}