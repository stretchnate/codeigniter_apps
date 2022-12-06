<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/2/2018
 * Time: 7:43 PM
 */

class Plaid extends CI_Controller {

    public function index() {
        die;
    }

    /**
     * @param string $update_type
     * @param int    $since
     * @throws Exception
     */
    public function updateTransactions($update_type = 'DEFAULT_UPDATE', $since = 7) {
        $last_update = new \DateTime();
        $last_update->sub(new DateInterval('P'.$since.'D'));
        $where = [
            'active' => true,
            'transactions_ready' => $update_type,
            'transactions_updated <' => $last_update->format('Y-m-d')];

        $iterator = new \Plaid\Connection\Iterator($where);
        while($iterator->valid()) {
            try {
                $transactions = new \API\REST\Plaid\Transactions();
                $transaction_response = $transactions->getTransactions(
                    $iterator->current()->getValues()->getAccessToken(),
                    $iterator->current()->getValues()->getPlaidAccountId(),
                    $last_update,
                    null,
                    500);

                if($transaction_response instanceof \Plaid\TransactionResponse) {
                    $creator = new \Plaid\Transaction\Creator($this->session->userdata('user_id'));
                    $creator->convertTransactionsToCategories($transaction_response);
                }
            } catch(Exception $e) {
                log_message('error', $e->getMessage() . "\n" . $e->getTraceAsString());
                //TODO need to email an admin
            }

            $iterator->next();
        }
    }
}