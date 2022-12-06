<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/9/18
 * Time: 8:48 PM
 */
require_once(APPPATH.'/controllers/AjaxResponse.php');

class Plaid extends _AjaxResponse {

    /**
     * Plaid constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * exchange the public token for an access token
     */
    public function getAccessToken() {
        $success = false;
        $message = 'There was a problem linking your account.';

        try {
            $api = new \API\REST\Plaid\Auth();

            $metadata = new \Plaid\Metadata($this->input->post('metadata', true));
            $response = $api->exchangeToken($this->input->post('public_token', true));

            if($response->getAccessToken() && $response->getItemId()) {
                $creator = new \Plaid\Account\Creator();
                $creator->run($metadata, $response, $this->session->userdata('user_id'), $this->input->post('existing_account'));

                $helper = new \Plaid\Response\Helper();
                $helper->saveResponse($response, 'TokenExchange');

                $success = true;
                $message = '';
            }
        } catch(Exception $e) {
            if($e->getCode() === EXCEPTION_CODE_VALIDATION) {
                $message = $e->getMessage();
            } else {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }
        }

        $this->jsonResponse($success, $message);
    }

    /**
     * @throws Exception
     */
    public function areTransactionsReady() {
        try {
            $success = true;
            $message = 'no';
            $values = new \Plaid\Connection\Values();
            $values->setPlaidAccountId($this->input->post('plaid_account_id', true));
            $connection = new \Plaid\Connection($values);

            if (in_array($connection->getValues()->getTransactionsReady(), ['INITIAL_UPDATE', 'HISTORICAL_UPDATE'])) {
                $message = 'yes';
            }
        } catch(Exception $e) {
            $error = $e->getMessage() . "\n" . $e->getTraceAsString();
            log_message('error', $error);
            $success = false;
        }

        $this->jsonResponse($success, $message);
    }

    /**
     * create categories and transactions
     */
    public function handleTransactions() {
        $success = false;
        try {
            $start_date = new DateTime($this->input->post('start_date', true));
            $item_id = $this->fetchItemId($this->input->post('plaid_account_id', true));
            $iterator = new \Plaid\Connection\Iterator($item_id, ['item_id' => $item_id]);
            if($iterator->valid()) {
                $transaction_helper = new \Transaction\Helper();
                while ($iterator->valid()) {
                    $last_transaction_date = $transaction_helper->getLastTransactionDate($iterator->current()->getValues()->getAccountId(), $this->session->userdata('user_id'));
                    $start_date = $last_transaction_date ? $last_transaction_date->add(new \DateInterval('P1D')) : $start_date;
                    $transactions = new \API\REST\Plaid\Transactions();
                    $transaction_response = $transactions->getTransactions(
                        $iterator->current()->getValues()->getAccessToken(),
                        $iterator->current()->getValues()->getPlaidAccountId(),
                        $start_date,
                        null,
                        500);

                    if ($transaction_response instanceof \Plaid\TransactionResponse) {
                        $creator = new \Plaid\Transaction\Creator($this->session->userdata('user_id'));
                        $creator->convertTransactionsToCategories($transaction_response);

                        $helper = new \Plaid\Response\Helper();
                        $helper->saveTransactionResponse($transaction_response);
                    }

                    $iterator->next();
                }

                $success = true;
            }

            $handler = new \Plaid\Connection\Handler();
            $handler->setTransactionsUpdatedDate($item_id, $this->input->post('plaid_account_id', true), new \DateTime());
        } catch(Exception $e) {
            $error = $e->getMessage() . "\n" . $e->getTraceAsString();
            log_message('error', $error);
            $success = false;
        }

        $this->jsonResponse($success);
    }

    /**
     * @param string $account_id
     * @return string
     * @throws Exception
     */
    private function fetchItemId($account_id) {
        $values = new \Plaid\Connection\Values();
        $values->setPlaidAccountId($account_id);
        $connection = new \Plaid\Connection($values);

        return $connection->getValues()->getItemId();
    }
}