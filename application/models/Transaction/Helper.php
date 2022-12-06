<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 6/9/2018
 * Time: 1:53 PM
 */

namespace Transaction;

/**
 * Class Helper
 *
 * @package Transaction
 */
class Helper extends \CI_Model {

    /**
     * @param $account_id
     * @return \DateTime|null
     * @throws \Exception
     */
    public function getLastTransactionDate($account_id, $owner_id) {
        $result = null;
        $account_dm = new \Budget_DataModel_AccountDM($account_id, $owner_id);
        $account_dm->loadCategories();
        $category_ids = [];
        foreach($account_dm->getCategories() as $category) {
            $category_ids[] = $category->getCategoryId();
        }

        if(!empty($category_ids)) {
            $this->db->where_in('to_category', $category_ids);
            $this->db->or_where_in('from_category', $category_ids);
            $this->db->order_by('transaction_date', 'desc');
            $query = $this->db->get('transactions');

            if(!$query) {
                throw new \Exception($this->db->error()['message']);
            }

            $result = $query->num_rows() > 0 ? new \DateTime($query->row()->transaction_date) : null;
        }

        return $result;
    }
}