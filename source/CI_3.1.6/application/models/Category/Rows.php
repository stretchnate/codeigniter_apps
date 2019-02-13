<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/7/2018
 * Time: 8:52 PM
 */

namespace Category;

/**
 * Class Rows
 *
 * @package Category
 */
class Rows extends \IteratorBase {

    /**
     * Rows constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->items = [];
    }

    /**
     * @param array $where
     * @return $this
     * @throws \Exception
     */
    public function load($where = []) {
        if($where) {
            $this->db->where($where);
        }

        $query = $this->db->get('booksummary');

        if(!$query) {
            throw new \Exception($this->db->error()['message']);
        }

        foreach($query->result() as $row) {
            $category = new \Budget_DataModel_CategoryDM();
            $category->setCategoryId($row->bookId)
                ->setCategoryName($row->bookName)
                ->setAmountNecessary($row->bookAmtNec)
                ->setCurrentAmount($row->bookAmtCurrent)
                ->setInterestBearing($row->interestBearing)
                ->setOwnerId($row->ownerId)
                ->setPriority($row->priority)
                ->setActive($row->active)
                ->setDueMonths($row->due_months)
                ->setDueDay($row->due_day)
                ->setParentAccountId($row->account_id)
                ->setPlaidCategory($row->plaid_category);

            $this->items[] = $category;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function current() {
        return $this->items[$this->key];
    }
}