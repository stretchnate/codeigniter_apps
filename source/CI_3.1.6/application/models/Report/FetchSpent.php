<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 7/16/2018
 * Time: 8:00 PM
 */

namespace Report;

class FetchSpent extends \CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function run(\Budget_DataModel_AccountDM $account) {
        $account->loadCategories();
        $data = [];
        foreach($account->getCategories() as $category) {
            $data[] = [
                'category' => $account->getAccountName() . ':' . $category->getCategoryName(),
                'amount' => $this->getAmountSpent($category->getCategoryId())
            ];
        }

        return $data;
    }

    public function getAmountSpent($category_id) {
        $now = new \DateTime();
        $then = clone $now;
        $then->sub(new \DateInterval('P30D'));

        $this->db->select('transaction_amount')
            ->from('transactions')
            ->where('from_category = '.$category_id.' AND transaction_date BETWEEN '.$then->format('Y-m-d').' AND '.$now->format('Y-m-d'));

        $query = $this->db->get();

        if(!$query) {
            throw new Exception($this->db->error()['message']);
        }

        return $this->createResultsArray($query->result());
    }

    private function createResultsArray($data) {
        $amount = 0;
        foreach($data as $row) {
            $amount = add($amount, $row->transaction_amount);
        }

        return $amount;
    }
}