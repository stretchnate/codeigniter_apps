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

            if($response->access_token && $response->item_id) {
                $creator = new \Plaid\Account\Creator();
                $creator->run($metadata, $response, $this->session->userdata('user_id'));

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
                while ($iterator->valid()) {
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
                    }

                    $iterator->next();
                }

                $success = true;
            }
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