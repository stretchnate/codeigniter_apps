<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 7/16/2018
 * Time: 7:58 PM
 */

class Report extends CI_Controller {

    public function fetchSpent($account_id) {
        try {
            $account_dm = new Budget_DataModel_AccountDM($account_id);
            $fs = new \Report\FetchSpent();
            $data = $fs->run($account_dm);

            echo json_encode(['success' => true, 'data' => $data]);
        } catch(Exception $e) {
            echo json_encode(['success' => false]);
        }
    }
}